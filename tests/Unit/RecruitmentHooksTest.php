<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\HRM\Recruitment\Tests\Unit;
use ksfraser\RelatedTo\DTO\RelatedRecordDto;
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../hooks.php';
class RecruitmentHooksTest extends TestCase {
    protected function setUp(): void { \DbStub::reset(); }
    private function hooks(): \hooks_ksf_FA_HRM_Recruitment {
        return new \hooks_ksf_FA_HRM_Recruitment();
    }
    public function testRespondersConstant(): void {
        $responders = \hooks_ksf_FA_HRM_Recruitment::RESPONDERS;
        $this->assertTrue(in_array('RELATED_TO_FIELDS', $responders, true));
        $this->assertTrue(in_array('RECRUITMENT_RESOLVE', $responders, true));
        $this->assertTrue(in_array('RECRUITMENT_FIELDS', $responders, true));
    }
    public function testRelatedToFieldsRecruitmentType(): void {
        $data = ['module' => 'ksf_FA_HRM_Recruitment', 'type' => 'recruitment', 'fields' => []];
        $this->hooks()->RELATED_TO_FIELDS($data);
        $this->assertSame('Candidate Name', $data['fields']['candidateName']);
        $this->assertSame('Position', $data['fields']['position']);
        $this->assertSame('Department', $data['fields']['department']);
    }
    public function testRelatedToFieldsNonRecruitmentTypeNoOp(): void {
        $data = ['module' => 'other', 'type' => 'payroll', 'fields' => ['a' => 1]];
        $this->hooks()->RELATED_TO_FIELDS($data);
        $this->assertSame(['a' => 1], $data['fields']);
    }
    public function testRelatedToFieldsMissingModuleError(): void {
        $data = ['type' => 'recruitment'];
        $this->hooks()->RELATED_TO_FIELDS($data);
        $this->assertArrayHasKey('error', $data);
    }
    public function testRecruitmentResolve(): void {
        \DbStub::setRows([[
            'id' => 11, 'candidate_name' => 'Eli Cruz', 'position' => 'Dev', 'department' => 'Eng',
            'status' => 'New', 'applied_on' => '2026-05-01', 'rating' => '', 'notes' => '', 'created_at' => '',
        ]]);
        $dto = new RelatedRecordDto('recruitment', '0', 'recruitment', '0', null);
        $data = ['recruitment_id' => 11, 'dto' => $dto];
        $this->hooks()->RECRUITMENT_RESOLVE($data);
        $this->assertArrayHasKey('result', $data);
        $this->assertSame('Eli Cruz', $data['result']['fields']['candidateName']);
        $this->assertSame($dto, $data['result']['dto']);
        $this->assertStringContainsString('recruitment_id=11', $data['result']['url']);
    }
    public function testRecruitmentResolveMissingIdError(): void {
        $data = ['dto' => new RelatedRecordDto('recruitment', '0', 'recruitment', '0', null)];
        $this->hooks()->RECRUITMENT_RESOLVE($data);
        $this->assertArrayHasKey('error', $data);
    }
    public function testRecruitmentResolveNonNumericIdError(): void {
        $data = ['recruitment_id' => 'abc', 'dto' => new RelatedRecordDto('recruitment', '0', 'recruitment', '0', null)];
        $this->hooks()->RECRUITMENT_RESOLVE($data);
        $this->assertArrayHasKey('error', $data);
    }
    public function testRecruitmentResolveMissingDtoError(): void {
        $data = ['recruitment_id' => 11];
        $this->hooks()->RECRUITMENT_RESOLVE($data);
        $this->assertArrayHasKey('error', $data);
    }
    public function testRecruitmentFields(): void {
        $data = ['fields' => []];
        $this->hooks()->RECRUITMENT_FIELDS($data);
        $this->assertSame('Candidate Name', $data['fields']['candidateName']);
        $this->assertSame('Department', $data['fields']['department']);
    }
}
