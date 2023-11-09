<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers</title>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="pb-5">

    <div class="container">
        <h1>Customers List</h1>
        <a href="{{ url('customers/create') }}" class="btn btn-primary my-2">Create New Customer</a>
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
        <div class="pagination">
            <button id="prevPage" class="btn btn-dark">Previous Page</button>
            <span id="currentPage" class="px-3">Page 1</span>
            <button id="nextPage" class="btn btn-dark">Next Page</button>
        </div>
    </div>


    <div id="error-message" style="color: red;"></div>

    <script>
        const apiUrl = '/api/v1/customers/';
        const errorDiv = document.getElementById('error-message');
        const tableBody = document.querySelector('tbody');
        let currentPage = 1; // Current page
        const itemsPerPage = 15; // Number of items to display per page
        let startIndex = 1; // Start index for the current page

        function fetchAndDisplayData(page) {
            const startIndex = (page - 1) * itemsPerPage + 1;
            fetch(`${apiUrl}?page=${page}&per_page=${itemsPerPage}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Clear the table before adding new data
                    tableBody.innerHTML = '';
                    displayCustomerData(data.data);
                })
                .catch(error => {
                    // Display the error message to the user
                    errorDiv.textContent = `Error fetching data: ${error}`;
                    console.error('Error fetching data:', error);
                });
        }

        function displayCustomerData(customerData) {
            customerData.forEach((customer, index) => {
                const row = tableBody.insertRow();
                const cell1 = row.insertCell(0);
                const cell2 = row.insertCell(1);
                const cell3 = row.insertCell(2);

                // Display the index (add the startIndex to start from the correct number)
                cell1.textContent = index + startIndex;
                cell2.textContent = customer.name;

                // Create action buttons and set their attributes
                const showButton = document.createElement('button');
                showButton.textContent = 'Show';
                showButton.className = 'btn btn-primary';
                showButton.addEventListener('click', () => {
                    const customerId = customer.id;
                    window.location.href = `/customer/show/${customerId}`;
                });

                const editButton = document.createElement('button');
                editButton.textContent = 'Edit';
                editButton.className = 'btn btn-info mx-2';
                editButton.addEventListener('click', () => {
                    window.location.href = `/customer/edit/${customer.id}`;
                });

                const deleteButton = document.createElement('button');
                deleteButton.textContent = 'Delete';
                deleteButton.className = 'btn btn-danger';
                deleteButton.addEventListener('click', () => {
                    const customerId = customer.id;
                    deleteCustomer(customerId, row);
                });

                // Append the action buttons to the cell
                cell3.appendChild(showButton);
                cell3.appendChild(editButton);
                cell3.appendChild(deleteButton);
            });
        }

        function deleteCustomer(customerId, row) {
            const deleteUrl = `/api/v1/customers/${customerId}`;
            fetch(deleteUrl, {
                    method: 'DELETE',
                })
                .then(response => {
                    if (response.ok) {
                        // Customer deleted successfully, remove the row from the table
                        tableBody.removeChild(row);
                        console.log('Customer deleted successfully');
                    } else {
                        alert('Failed to delete customer');
                    }
                })
                .catch(error => {
                    console.error('Error deleting customer:', error);
                });
        }

        // Pagination buttons event listeners
        document.getElementById('prevPage').addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                startIndex -= itemsPerPage;
                fetchAndDisplayData(currentPage);
                updatePagination();
            }
        });

        document.getElementById('nextPage').addEventListener('click', () => {
            currentPage++;
            startIndex += itemsPerPage;
            fetchAndDisplayData(currentPage);
            updatePagination();
        });

        function updatePagination() {
            const currentPageSpan = document.getElementById('currentPage');
            currentPageSpan.textContent = `Page ${currentPage}`;
        }

        // Initial data load
        fetchAndDisplayData(currentPage);
        updatePagination();
    </script>


</body>

</html>