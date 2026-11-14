-- ksf_FA_HRM_Recruitment: recruitment pipeline table

CREATE TABLE IF NOT EXISTS `0_hrm_recruitment` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `candidate_name` VARCHAR(100) NOT NULL DEFAULT '',
  `position`     VARCHAR(100) NOT NULL DEFAULT '',
  `department`   VARCHAR(100) NOT NULL DEFAULT '',
  `status`       VARCHAR(30)  NOT NULL DEFAULT 'New',
  `applied_on`   DATE         NULL,
  `rating`       VARCHAR(10)  NOT NULL DEFAULT '',
  `notes`        TEXT,
  `created_at`   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_applied_on` (`applied_on`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
