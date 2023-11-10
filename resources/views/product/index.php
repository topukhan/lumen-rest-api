<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="pb-5">

    <div class="container">
        <h1>Product List</h1>
        <a href="<?php echo url('/products/create'); ?>" class="btn btn-primary my-2">Create New Product</a>

        <table class="table table-striped table-bordered text-center">
            <thead>
                <tr>
                    <th>Customer ID</th>
                    <th>Name</th>
                    <th>Action</th>
                    <!-- more columns as needed -->
                </tr>
            </thead>
            <tbody>
                <!-- Data rows will be added dynamically using JavaScript -->
            </tbody>
        </table>
    </div>


    <div id="error-message" style="color: red;"></div>

    <script>
    const apiUrl = '/api/v1/products/';
    const errorDiv = document.getElementById('error-message');
    const tableBody = document.querySelector('tbody');

    function fetchAndDisplayData() {
        fetch(apiUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Clear the table before adding new data
                tableBody.innerHTML = '';
                displayProductData(data);
            })
            .catch(error => {
                // Display the error message to the user
                errorDiv.textContent = `Error fetching data: ${error}`;
                console.error('Error fetching data:', error);
            });
    }

    function displayProductData(productData) {
        productData.forEach((product, index) => {
            const row = tableBody.insertRow();
            const cell1 = row.insertCell(0);
            const cell2 = row.insertCell(1);
            const cell3 = row.insertCell(2);

            // Display the index
            cell1.textContent = index + 1;
            cell2.textContent = product.name;

            // Create action buttons and set their attributes
            const showButton = document.createElement('button');
            showButton.textContent = 'Show';
            showButton.className = 'btn btn-primary';
            showButton.addEventListener('click', () => {
                const productId = product.id;
                window.location.href = `/products/show/${productId}`;
            });

            const editButton = document.createElement('button');
            editButton.textContent = 'Edit';
            editButton.className = 'btn btn-info mx-2';
            editButton.addEventListener('click', () => {
                window.location.href = `/products/edit/${product.id}`;
            });

            const deleteButton = document.createElement('button');
            deleteButton.textContent = 'Delete';
            deleteButton.className = 'btn btn-danger';
            deleteButton.addEventListener('click', () => {
                const productId = product.id;
                deleteProduct(productId, row);
            });

            // Append the action buttons to the cell
            cell3.appendChild(showButton);
            cell3.appendChild(editButton);
            cell3.appendChild(deleteButton);
        });
    }

    function deleteProduct(productId, row) {
        const deleteUrl = `/api/v1/products/delete/${productId}`;
        fetch(deleteUrl, {
                method: 'DELETE',
            })
            .then(response => {
                if (response.ok) {
                    // Product deleted successfully, remove the row from the table
                    tableBody.removeChild(row);
                    console.log('Product deleted successfully');
                } else {
                    alert('Failed to delete product');
                }
            })
            .catch(error => {
                console.error('Error deleting product:', error);
            });
    }

    // Initial data load
    fetchAndDisplayData();
</script>



</body>

</html>