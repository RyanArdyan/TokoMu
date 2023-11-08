<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Invoice with Signature</title>
<style>
  body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
  }
  .invoice {
    width: 800px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #000;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }
  .invoice-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
  }
  .invoice-header h1 {
    margin: 0;
  }
  .invoice-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
  }
  .invoice-table th, .invoice-table td {
    border: 1px solid #000;
    padding: 10px;
    text-align: left;
  }
  .signature {
    margin-top: 40px;
  }
  .signature p {
    margin-bottom: 10px;
  }
</style>
</head>
<body>
  <div class="invoice">
    <div class="invoice-header">
      <h1>Invoice</h1>
      <p>Date: August 23, 2023</p>
    </div>
    <table class="invoice-table">
      <thead>
        <tr>
          <th>Description</th>
          <th>Quantity</th>
          <th>Price</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Item 1</td>
          <td>2</td>
          <td>$50</td>
          <td>$100</td>
        </tr>
        <tr>
          <td>Item 2</td>
          <td>3</td>
          <td>$30</td>
          <td>$90</td>
        </tr>
      </tbody>
    </table>
    <div class="signature">
      <p>Customer Signature:</p>
      <p>Date:</p>
    </div>
  </div>
</body>
</html>
