<?php

declare(strict_types=1);

namespace Ksfraser\FrontAccounting\HRM\Recruitment\App;

use ksfraser\FrontAccounting\Common\App\AbstractAppShell;
use ksfraser\FrontAccounting\Common\Menu\FAModuleMenu;
use Ksfraser\FrontAccounting\HRM\Recruitment\Controller\RecruitmentTabController;

class RecruitmentAppShell extends AbstractAppShell
{
    public function __construct()
    {
        parent::__construct('recruitment', 'index.php', 'view', 'recruitment');

        $this->add_module(_("Recruitment"));

        $menu = new FAModuleMenu(
            'modules/ksf_FA_HRM_Recruitment/index.php',
            'view',
            ''
        );

        $menu->addItem('recruitment', _("Recruitment"), MENU_INQUIRY)
             ->registerWithApp($this, 'SA_RECRUITMENT_VIEW');

        $this->add_extensions();
    }
}
