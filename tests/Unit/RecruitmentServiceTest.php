<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\HRM\Recruitment\Tests\Unit;
use Ksfraser\FrontAccounting\HRM\Recruitment\Repository\RecruitmentRepository;
use Ksfraser\FrontAccounting\HRM\Recruitment\Service\RecruitmentService;
use ksfraser\RelatedTo\DTO\RelatedRecordDto;
use PHPUnit\Framework\TestCase;
class RecruitmentServiceTest extends TestCase {
    protected function setUp(): void { \DbStub::reset(); }
    public function testResolveByIdNotFoundStructure(): void {
        \DbStub::setRows([]);
        $service = new RecruitmentService();
        $result = $service->resolveById(999);
        $this->assertArrayHasKey('dto', $result);
        $this->assertArrayHasKey('fields', $result);
        $this->assertArrayHasKey('labels', $result);
        $this->assertArrayHasKey('url', $result);
        $this->assertArrayHasKey('label', $result);
        $this->assertSame('Candidate #999', $result['label']);
        $this->assertSame('#', $result['url']);
        $this->assertInstanceOf(RelatedRecordDto::class, $result['dto']);
    }
    public function testResolveByIdFound(): void {
        \DbStub::setRows([[
            'id' => 4, 'candidate_name' => 'Dana Lee', 'position' => 'Architect',
            'department' => 'IT', 'status' => 'Offer', 'applied_on' => '2026-04-01',
            'rating' => 'hot', 'notes' => '', 'created_at' => '',
        ]]);
        $service = new RecruitmentService();
        $result = $service->resolveById(4);
        $this->assertSame('Dana Lee', $result['fields']['candidateName']);
        $this->assertSame('Architect', $result['fields']['position']);
        $this->assertSame('Offer', $result['fields']['status']);
        $this->assertSame('2026-04-01', $result['fields']['appliedOn']);
        $this->assertSame('IT', $result['fields']['department']);
        $this->assertSame('Dana Lee — Architect — Offer', $result['label']);
        $this->assertSame('/modules/ksf_FA_HRM_Recruitment/pages/recruitment_form.php?action=edit&recruitment_id=4', $result['url']);
        $this->assertInstanceOf(RelatedRecordDto::class, $result['dto']);
        $this->assertSame('4', $result['dto']->rhsId);
    }
    public function testListAllReturnsStructure(): void {
        \DbStub::setRows([
            ['id' => 1, 'candidate_name' => 'A', 'position' => 'P1', 'department' => 'D1', 'status' => 'New', 'applied_on' => '2026-01-01', 'rating' => '', 'notes' => '', 'created_at' => ''],
            ['id' => 2, 'candidate_name' => 'B', 'position' => 'P2', 'department' => 'D2', 'status' => 'Interview', 'applied_on' => '2026-02-01', 'rating' => '', 'notes' => '', 'created_at' => ''],
        ]);
        $service = new RecruitmentService();
        $results = $service->listAll();
        $this->assertCount(2, $results);
        $this->assertSame(1, $results[0]['id']);
        $this->assertSame('A', $results[0]['fields']['candidateName']);
        $this->assertSame(2, $results[1]['id']);
        $this->assertInstanceOf(RelatedRecordDto::class, $results[0]['dto']);
        $this->assertArrayHasKey('labels', $results[0]);
        $this->assertArrayHasKey('url', $results[0]);
    }
    public function testListAllEmpty(): void {
        \DbStub::setRows([]);
        $service = new RecruitmentService();
        $this->assertSame([], $service->listAll());
    }
    public function testFieldMapEqualsLabelMap(): void {
        $service = new RecruitmentService();
        $this->assertSame($service->labelMap(), $service->fieldMap());
    }
    public function testLabelMapKeysAndValues(): void {
        $service = new RecruitmentService();
        $labels = $service->labelMap();
        $this->assertSame([
            'candidateName' => 'Candidate Name',
            'position' => 'Position',
            'status' => 'Status',
            'appliedOn' => 'Applied On',
            'department' => 'Department',
        ], $labels);
    }
    public function testRepositoryInjection(): void {
        $repo = new RecruitmentRepository();
        $service = new RecruitmentService($repo);
        $ref = new \ReflectionProperty(RecruitmentService::class, 'repo');
        $ref->setAccessible(true);
        $this->assertSame($repo, $ref->getValue($service));
    }
}
