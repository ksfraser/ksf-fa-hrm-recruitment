# Business Requirements — ksf_FA_HRM_Recruitment

**Package:** `ksfraser/ksf-fa-hrm-recruitment`
**Version:** `1.0.0`
**Namespace:** `Ksfraser\FrontAccounting\HRM\Recruitment\*`
**Module sequence:** HR (HRM Recruitment)

---

## BR-HR-001 — Recruitment resolution ownership

**Status:** IMPLEMENTED

The system SHALL provide `ksf_FA_HRM_Recruitment` as the owning module for
recruitment/pipeline data spun off from HRM Recruitment tab. HRM defers to
Recruitment for recruitment display.

1. Recruitment SHALL own the `recruitment` type in the RelatedTo registry.
2. Recruitment SHALL answer `RELATED_TO_FIELDS` for the `recruitment` type.
3. Recruitment SHALL answer `RECRUITMENT_RESOLVE` by building resolved DTOs from
   `0_hrm_recruitment` data.
4. Consuming modules MAY override by registering their own `RECRUITMENT_RESOLVE`
   listener.

---

## BR-HR-002 — Recruitment display default

**Status:** IMPLEMENTED

The recruitment display default SHALL show Candidate Name — Position — Status.

1. `RecruitmentDTO::getDisplayLabel()` SHALL return non-empty fields joined with ` — `.
2. When all display fields are empty, return `Candidate #<id>`.
3. All values SHALL be HTML-escaped at render time.

---

## BR-HR-003 — Resolved DTO for RelatedTo

**Status:** IMPLEMENTED

Recruitment SHALL build resolved display data (DTO + fields + labels + url) for RelatedTo display.

1. Resolved data wraps a `RelatedRecordDto` with resolved fields,
   overridable labels, and a URL.
2. `fieldMap()` returns the field name => display label mapping.
3. `listAll()` returns resolved recruitment data for summary table display.

---

## BR-HR-004 — Recruitment pipeline fields

**Status:** IMPLEMENTED

Recruitment SHALL store pipeline fields: candidate name, position, department,
status, applied on date, rating, notes.

1. All fields stored in `0_hrm_recruitment`.
2. Default status is `New`.
3. Status pipeline: New → Screening → Interview → Offered → Hired → Rejected.

