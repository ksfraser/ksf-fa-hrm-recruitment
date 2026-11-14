<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\HRM\Recruitment\Tests\Unit;
use Ksfraser\FrontAccounting\HRM\Recruitment\DTO\RecruitmentDTO;
use PHPUnit\Framework\TestCase;
class RecruitmentDtoTest extends TestCase {
    public function testGetDisplayLabel(): void {
        $dto = new RecruitmentDTO(7, 'Alice Smith', 'Senior Developer', 'Engineering', 'Interview', '2026-10-01', 'hot', 'Strong candidate', '');
        $this->assertSame('Alice Smith — Senior Developer — Interview', $dto->getDisplayLabel());
    }
    public function testGetDisplayLabelMinimal(): void {
        $dto = new RecruitmentDTO(3, '', '', '', '', '', '', '', '');
        $this->assertSame('Candidate #3', $dto->getDisplayLabel());
    }
    public function testToArrayShape(): void {
        $dto = new RecruitmentDTO(7, 'Alice Smith', 'Senior Developer', 'Engineering', 'Interview', '2026-10-01', 'hot', 'Strong candidate', '');
        $array = $dto->toArray();
        $this->assertSame(7, $array['id']);
        $this->assertSame('Alice Smith', $array['candidate_name']);
        $this->assertSame('Senior Developer', $array['position']);
        $this->assertSame('Interview', $array['status']);
    }
    public function testFromArrayReconstructs(): void {
        $dto = RecruitmentDTO::fromArray(array('id' => 9, 'candidate_name' => 'Bob Jones', 'position' => 'Junior Dev', 'department' => 'IT', 'status' => 'New', 'applied_on' => '2026-11-01', 'rating' => 'warm', 'notes' => 'Good', 'created_at' => '2026-01-01'));
        $this->assertSame(9, $dto->id);
        $this->assertSame('Bob Jones', $dto->candidateName);
        $this->assertSame('Junior Dev', $dto->position);
        $this->assertSame('IT', $dto->department);
        $this->assertSame('New', $dto->status);
    }
}
