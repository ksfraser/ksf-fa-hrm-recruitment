<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\HRM\Recruitment\DTO;
final class RecruitmentDTO {
    public $id;
    public $candidateName;
    public $position;
    public $department;
    public $status;
    public $appliedOn;
    public $rating;
    public $notes;
    public $createdAt;
    public function __construct(int $id, string $candidateName = '', string $position = '', string $department = '', string $status = 'New', string $appliedOn = '', string $rating = '', string $notes = '', string $createdAt = '') {
        $this->id = $id; $this->candidateName = $candidateName; $this->position = $position; $this->department = $department;
        $this->status = $status; $this->appliedOn = $appliedOn; $this->rating = $rating; $this->notes = $notes; $this->createdAt = $createdAt;
    }
    public function getDisplayLabel(): string {
        $parts = array();
        if ($this->candidateName !== '') { $parts[] = $this->candidateName; }
        if ($this->position !== '') { $parts[] = $this->position; }
        if ($this->status !== '') { $parts[] = $this->status; }
        return $parts === [] ? _('Candidate #') . (string) $this->id : implode(' — ', $parts);
    }
    public function toArray(): array {
        return array('id' => $this->id, 'candidate_name' => $this->candidateName, 'position' => $this->position, 'department' => $this->department, 'status' => $this->status, 'applied_on' => $this->appliedOn, 'rating' => $this->rating, 'notes' => $this->notes, 'created_at' => $this->createdAt);
    }
    public static function fromArray(array $data): self {
        return new self((int) ($data['id'] ?? 0), (string) ($data['candidate_name'] ?? ''), (string) ($data['position'] ?? ''), (string) ($data['department'] ?? ''), (string) ($data['status'] ?? 'New'), (string) ($data['applied_on'] ?? ''), (string) ($data['rating'] ?? ''), (string) ($data['notes'] ?? ''), (string) ($data['created_at'] ?? ''));
    }
}
