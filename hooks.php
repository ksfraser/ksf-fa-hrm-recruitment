<?php
declare(strict_types=1);
require_once __DIR__ . '/ComposerDependencies.php';
\Ksfraser\FrontAccounting\HRM\Recruitment\Utils\ComposerDependencies::ensure(__DIR__);
$autoload_path = dirname(__FILE__) . '/vendor/autoload.php';
if (file_exists($autoload_path)) { require_once $autoload_path; }
require_once __DIR__ . '/includes/common.inc';
require_once __DIR__ . '/includes/events.inc';
use ksfraser\RelatedTo\DTO\RelatedRecordDto;
define('SS_ksf_FA_HRM_Recruitment', 162 << 8);
if (!defined('SA_RECRUITMENT_VIEW')) {
    define('SA_RECRUITMENT_VIEW', SS_ksf_FA_HRM_Recruitment | 1);
    define('SA_RECRUITMENT_MANAGE', SS_ksf_FA_HRM_Recruitment | 2);
}
class hooks_ksf_FA_HRM_Recruitment extends hooks {
    const RESPONDERS = array('RELATED_TO_FIELDS', 'RECRUITMENT_RESOLVE', 'RECRUITMENT_FIELDS');
    var $module_name = 'ksf_FA_HRM_Recruitment';
    var $version = '1.0.0';
    function install_tabs($app) { }
    function install_options($app) { }
    function install_access() {
        $security_sections[SS_ksf_FA_HRM_Recruitment] = _("Recruitment Management");
        $security_areas['SA_RECRUITMENT_VIEW'] = array(SA_RECRUITMENT_VIEW, _("View recruitment"));
        $security_areas['SA_RECRUITMENT_MANAGE'] = array(SA_RECRUITMENT_MANAGE, _("Manage recruitment"));
        return array($security_areas, $security_sections);
    }
    function activate_extension($company, $check_only = true) {
        if ($check_only) { return true; }
        \Ksfraser\FrontAccounting\HRM\Recruitment\Utils\ComposerDependencies::ensure(__DIR__);
        $this->update_databases($company, array('0_hrm_recruitment.sql' => array('hrm_recruitment')), false);
        return true;
    }
    private function ensure_composer_dependencies() {
        \Ksfraser\FrontAccounting\HRM\Recruitment\Utils\ComposerDependencies::ensure(__DIR__);
    }
    public function getModuleConstants(&$data, $opts = null) {
        $data['constants'] = array('SS_ksf_FA_HRM_Recruitment' => SS_ksf_FA_HRM_Recruitment, 'SA_RECRUITMENT_VIEW' => SA_RECRUITMENT_VIEW, 'SA_RECRUITMENT_MANAGE' => SA_RECRUITMENT_MANAGE);
        return true;
    }
    public function getModuleCapabilities(&$data, $opts = null) {
        $data['capabilities'] = self::RESPONDERS;
        return true;
    }
    public function hasCapability(&$data, $opts = null) {
        $capability = is_array($opts) && isset($opts['capability']) ? $opts['capability'] : null;
        if ($capability === null && isset($data['capability'])) { $capability = $data['capability']; }
        $data['has_capability'] = in_array($capability, self::RESPONDERS, true);
        return $data['has_capability'];
    }
    public function respondToCapabilityRequest(&$data, $opts = null) {
        $request = is_array($opts) && isset($opts['request']) ? $opts['request'] : (isset($data['request']) ? $data['request'] : 'capabilities');
        $data['request'] = $request;
        $data['module'] = $this->module_name;
        if (strpos($request, 'has:') === 0) { return $this->hasCapability($data, array('capability' => substr($request, 4))); }
        switch ($request) {
            case 'capabilities': return $this->getModuleCapabilities($data, $opts);
            case 'constants': return $this->getModuleConstants($data, $opts);
            default: $data['error'] = 'Unknown request type: ' . $request; return null;
        }
    }
    public function RELATED_TO_FIELDS(&$data, $opts = null) {
        if (!isset($data['module']) || !is_string($data['module'])) { $data['error'] = 'RELATED_TO_FIELDS requires data[module]'; return null; }
        if (!isset($data['type']) || !is_string($data['type'])) { $data['error'] = 'RELATED_TO_FIELDS requires data[type]'; return null; }
        if ($data['type'] !== 'recruitment') { return null; }
        if (!isset($data['fields']) || !is_array($data['fields'])) { $data['fields'] = array(); }
        $data['fields'] = array_merge($data['fields'], array(
            'candidateName' => _('Candidate Name'),
            'position' => _('Position'),
            'status' => _('Status'),
            'appliedOn' => _('Applied On'),
            'department' => _('Department'),
        ));
        return null;
    }
    public function RECRUITMENT_RESOLVE(&$data, $opts = null) {
        if (!isset($data['recruitment_id']) || !is_numeric($data['recruitment_id'])) { $data['error'] = 'RECRUITMENT_RESOLVE requires data[recruitment_id]'; return null; }
        if (!isset($data['dto']) || !$data['dto'] instanceof RelatedRecordDto) { $data['error'] = 'RECRUITMENT_RESOLVE requires data[dto] as RelatedRecordDto'; return null; }
        $service = new \Ksfraser\FrontAccounting\HRM\Recruitment\Service\RecruitmentService();
        $data['result'] = $service->resolveById((int) $data['recruitment_id'], $data['dto']);
        return null;
    }
    public function RECRUITMENT_FIELDS(&$data, $opts = null) {
        $data['fields'] = array(
            'candidateName' => _('Candidate Name'),
            'position' => _('Position'),
            'status' => _('Status'),
            'appliedOn' => _('Applied On'),
            'department' => _('Department'),
        );
        return null;
    }
}
