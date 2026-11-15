<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\HRM\Recruitment\Tests\Unit;
use Ksfraser\FrontAccounting\HRM\Recruitment\Repository\RecruitmentRepository;
use PHPUnit\Framework\TestCase;
class RecruitmentRepositoryTest extends TestCase {
    protected function setUp(): void { \DbStub::reset(); }
    public function testFindAllReturnsRows(): void {
        \DbStub::setRows([[
            'id' => 1, 'candidate_name' => 'Alice Smith', 'position' => 'Senior Developer',
            'department' => 'Engineering', 'status' => 'Interview', 'applied_on' => '2026-10-01',
            'rating' => 'hot', 'notes' => 'Strong', 'created_at' => '',
        ]]);
        $repo = new RecruitmentRepository();
        $rows = $repo->findAll();
        $this->assertCount(1, $rows);
        $this->assertSame('Alice Smith', $rows[0]['candidate_name']);
        $this->assertStringContainsString('SELECT * FROM', \DbStub::$lastSql);
        $this->assertStringContainsString('hrm_recruitment', \DbStub::$lastSql);
    }
    public function testFindAllDbFailureReturnsEmpty(): void {
        \DbStub::$queryResult = false;
        $repo = new RecruitmentRepository();
        $this->assertSame([], $repo->findAll());
    }
    public function testFindByIdReturnsRow(): void {
        \DbStub::setRows([[
            'id' => 7, 'candidate_name' => 'Bob Jones', 'position' => 'Manager',
            'department' => 'Sales', 'status' => 'New', 'applied_on' => '2026-02-01',
            'rating' => '', 'notes' => '', 'created_at' => '',
        ]]);
        $repo = new RecruitmentRepository();
        $row = $repo->findById(7);
        $this->assertSame(7, $row['id']);
        $this->assertSame('Bob Jones', $row['candidate_name']);
        $this->assertStringContainsString('WHERE id = 7', \DbStub::$lastSql);
    }
    public function testFindByIdNotFoundReturnsNull(): void {
        \DbStub::setRows([]);
        $repo = new RecruitmentRepository();
        $this->assertNull($repo->findById(99));
    }
    public function testSaveReturnsInsertId(): void {
        \DbStub::$insertId = 42;
        $repo = new RecruitmentRepository();
        $id = $repo->save([
            'candidate_name' => "Carol O'Neil", 'position' => 'Lead', 'department' => 'IT',
            'status' => 'Offer', 'applied_on' => '2026-03-01', 'rating' => 'warm', 'notes' => 'Great',
        ]);
        $this->assertSame(42, $id);
        $this->assertStringContainsString('INSERT INTO', \DbStub::$lastSql);
        $this->assertStringContainsString('hrm_recruitment', \DbStub::$lastSql);
        $this->assertTrue(in_array("Carol O'Neil", \DbStub::$escaped, true));
    }
    public function testUpdateCallsDbQuery(): void {
        $repo = new RecruitmentRepository();
        $repo->update(3, ['status' => 'Hired', 'rating' => 'hot']);
        $this->assertStringContainsString('UPDATE', \DbStub::$lastSql);
        $this->assertStringContainsString('hrm_recruitment', \DbStub::$lastSql);
        $this->assertStringContainsString('WHERE id = 3', \DbStub::$lastSql);
        $this->assertTrue(in_array('Hired', \DbStub::$escaped, true));
    }
    public function testUpdateNoSetsDoesNothing(): void {
        $repo = new RecruitmentRepository();
        $repo->update(3, []);
        $this->assertNull(\DbStub::$lastSql);
    }
    public function testDeleteCallsDbQuery(): void {
        $repo = new RecruitmentRepository();
        $repo->delete(5);
        $this->assertStringContainsString('DELETE FROM', \DbStub::$lastSql);
        $this->assertStringContainsString('hrm_recruitment', \DbStub::$lastSql);
        $this->assertStringContainsString('WHERE id = 5', \DbStub::$lastSql);
    }
}
