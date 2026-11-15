<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\HRM\Recruitment\Service;
use Ksfraser\FrontAccounting\HRM\Recruitment\DTO\RecruitmentDTO;
use Ksfraser\FrontAccounting\HRM\Recruitment\Repository\RecruitmentRepository;
use ksfraser\RelatedTo\DTO\RelatedRecordDto;
/**
 * @BABOK Related: BR-HR-001
 * @BABOK Related: BR-HR-003
 */
final class RecruitmentService {
    private $repo;
    /**
     * @BABOK Related: BR-HR-001
     */
    public function __construct(?RecruitmentRepository $repo = null) { $this->repo = $repo ?? new RecruitmentRepository(); }
    /**
     * @BABOK Related: BR-HR-001
     * @BABOK Related: BR-HR-003
     */
    public function resolveById(int $id, ?RelatedRecordDto $dto = null): array {
        $row = $this->repo->findById($id);
        if ($row === null) {
            return array('dto' => new RelatedRecordDto('recruitment', '0', 'recruitment', '0', null), 'fields' => $this->fieldMap(), 'labels' => $this->labelMap(), 'url' => '#', 'label' => _('Candidate #') . (string) $id);
        }
        $r = RecruitmentDTO::fromArray($row);
        $dto = $dto ?? new RelatedRecordDto('recruitment', (string) $r->id, 'recruitment', (string) $r->id, null);
        return array('dto' => $dto, 'fields' => array('candidateName' => $r->candidateName, 'position' => $r->position, 'status' => $r->status, 'appliedOn' => $r->appliedOn, 'department' => $r->department), 'labels' => $this->labelMap(), 'url' => '/modules/ksf_FA_HRM_Recruitment/pages/recruitment_form.php?action=edit&recruitment_id=' . $r->id, 'label' => $r->getDisplayLabel());
    }
    /**
     * @BABOK Related: BR-HR-001
     * @BABOK Related: BR-HR-003
     */
    public function listAll(): array {
        $rows = $this->repo->findAll();
        $results = array();
        foreach ($rows as $row) {
            $r = RecruitmentDTO::fromArray($row);
            $results[] = array('id' => $r->id, 'dto' => new RelatedRecordDto('recruitment', (string) $r->id, 'recruitment', (string) $r->id, null), 'fields' => array('candidateName' => $r->candidateName, 'position' => $r->position, 'status' => $r->status, 'appliedOn' => $r->appliedOn, 'department' => $r->department), 'labels' => $this->labelMap(), 'url' => '/modules/ksf_FA_HRM_Recruitment/pages/recruitment_form.php?action=edit&recruitment_id=' . $r->id, 'label' => $r->getDisplayLabel());
        }
        return $results;
    }
    /**
     * @BABOK Related: BR-HR-003
     */
    public function fieldMap(): array { return $this->labelMap(); }
    /**
     * @BABOK Related: BR-HR-003
     */
    public function labelMap(): array {
        return array('candidateName' => _('Candidate Name'), 'position' => _('Position'), 'status' => _('Status'), 'appliedOn' => _('Applied On'), 'department' => _('Department'));
    }
}
