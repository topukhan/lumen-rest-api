<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Edit</title>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="pb-5">

    <div class="container">
        <h1>Edit Customer</h1>
        <form id="customer-edit-form">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Name: <span class="text-danger">*</span> </label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="image" class="form-label">Image:</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                </div>
                <div class="col-md-6 mb-3">
                    <button type="submit" class="btn btn-primary">Update Customer</button>
                    <a href="{{ url('customers') }}" class="btn btn-danger ml-2">Back</a>
                    <div id="error-message" class="text-danger mt-2"></div>
                    <div id="message" class="text-success mt-2"></div>
                </div>
                <div class="col-md-6 mb-3">
                    <img src="" alt="Customer Image" id="customerImage" class="img-fluid  rounded">
                </div>
            </div>
        </form>

    </div>

    <script>
        // Populate customer info
        const customerId = window.location.pathname.split('/').pop();
        const apiUrlShow = `/api/v1/customers/${customerId}`;
        const errorDiv = document.getElementById('error-message');
        const messageDiv = document.getElementById('message');
        fetch(apiUrlShow)
            .then(response => response.json())
            .then(responseData => {
                const data = responseData.data;
                console.log(data);
                document.getElementById('name').value = `${data.name}`;

                // Display the existing image if it exists
                if (data.image && isImageFileName(data.image)) {
                    customerImage.src = `${data.image}`;
                } else {
                    customerImage.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error fetching customer data:', error);
            });
        // extract the image name from the image element
        function isImageFileName(fileName) {
            // regular expression pattern for image file extensions
            const imageFilePattern = /\.(jpg|jpeg|png|gif|jfif)$/i;

            // test if the file name match
            return imageFilePattern.test(fileName);
        }

        // update customers
        const apiUrlUpdate = `/api/v1/customers/${customerId}`;

        document.getElementById('customer-edit-form').addEventListener('submit', (event) => {
            event.preventDefault();

            // assign values from the populated fields
            const name = document.getElementById('name').value;

            const updatedCustomerData = {
                name: name,
            };

            // Handle image update
            // const imageInput = document.getElementById('image');
            // if (imageInput.files.length > 0) {
            //     updatedCustomerData.image = imageInput.files[0];
            // }

            // Create a FormData object to handle image updates
            // const formData = new FormData();
            // for (const key in updatedCustomerData) {
            //     formData.append(key, updatedCustomerData[key]);
            // }


            // Make a PUT request to update the customer
            fetch(apiUrlUpdate, {
                    method: 'PUT', // Use PUT for updates
                    body: JSON.stringify(updatedCustomerData), // Use FormData for sending image data
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json', // Set the appropriate content type
                    },
                    redirect: 'follow', // Allow the fetch to follow redirects
                })
                .then(response => {
                    if (response.status === 422) {
                        // The response status is 422, indicating validation errors
                        return response.json()
                            .then(data => {
                                // Handle validation errors
                                messageDiv.style.color = 'red';
                                messageDiv.textContent = 'Validation errors: ' + Object.values(data.errors).join(', ');
                            });
                    }
                    if (response.ok) {
                        // Handle success
                        messageDiv.style.color = 'green';
                        messageDiv.textContent = 'Customer updated successfully.';
                    } else {
                        // Handle other errors
                        messageDiv.style.color = 'red';
                        messageDiv.textContent = 'Failed to update customer.';
                    }
                })
                .catch(error => {
                    // Handle network errors
                    console.error('Network error:', error);
                });
        });
    </script>
</body>

</html>