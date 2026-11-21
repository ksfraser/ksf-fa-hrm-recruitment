<?php

declare(strict_types=1);

namespace Ksfraser\FrontAccounting\HRM\Recruitment\App;

use ksfraser\FrontAccounting\Common\App\AbstractFrontController;

class RecruitmentFrontController extends AbstractFrontController
{
    public function __construct(
        RecruitmentAppShell $appShell,
        string $fallbackSecurityArea,
        string $path_to_root
    ) {
        parent::__construct($appShell, $fallbackSecurityArea, $path_to_root);
    }
}
