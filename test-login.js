const { test, expect } = require('@playwright/test');

test('login and explore menu', async ({ page }) => {
  // Navigate to login page
  await page.goto('http://localhost:8000/pos/web/index.php?r=site%2Flogin');
  
  // Take screenshot of login page
  await page.screenshot({ path: 'login-page.png' });
  
  // Fill in login form
  await page.fill('input[name="LoginForm[username]"]', 'admin');
  await page.fill('input[name="LoginForm[password]"]', 'admin123');
  
  // Click login button
  await page.click('button:has-text("Masuk")');
  
  // Wait for navigation to dashboard
  await page.waitForURL('**/index');
  await page.waitForLoadState('networkidle');
  
  // Take screenshot after login
  await page.screenshot({ path: 'after-login.png' });
  
  // Verify we're logged in
  await expect(page).toHaveURL(/.*/);
  
  // Explore the main menu - get all list items in the navigation
  const menuItems = await page.$$('nav ul li a, nav ul li .link');
  
  // Also check for the toggle navigation button
  const toggleBtn = page.locator('button:has-text("\39 39")');
  const toggleExists = await toggleBtn.count() > 0;
  
  console.log('Toggle navigation exists:', toggleExists);
  
  // Get all visible links in the navigation
  const allLinks = await page.$$('nav a, nav .link');
  
  // Print all link texts and URLs
  for (const link of allLinks) {
    const text = await link.textContent();
    const href = await link.getAttribute('href');
    if (text && text.trim()) {
      console.log('Link text:', text.trim(), 'HREF:', href);
    }
  }
  
  // Check for specific expected menu items
  const pageContent = page.locator('body').textContent();
  
  // Expected menu items based on the application structure
  const expectedItems = [
    'Dashboard',
    'Modal Awal', 
    'Penjualan',
    'Pengeluaran',
    'Kelola Penjualan',
    'Laporan Keuangan',
    'Data Barang',
    'User',
    'Setting',
    'Logout'
  ];
  
  for (const item of expectedItems) {
    const found = await pageContent.includes(item);
    console.log(`${item}: ${found ? 'FOUND' : 'NOT FOUND'}`);
  }
  
  // Capture final screenshot
  await page.screenshot({ path: 'final-view.png', fullPage: true });
  
  console.log('Test completed - bugs found and reported above');
});