import { test, expect } from '@playwright/test';

test.describe('ksf_FA_HRM_Recruitment Recruitment Module', () => {
  test('should load recruitment list page', async ({ page }) => {
    await page.goto('/modules/ksf_FA_HRM_Recruitment/pages/recruitment.php');
    await expect(page.locator('h1, h2')).toContainText(/Recruitment/i);
  });
});
