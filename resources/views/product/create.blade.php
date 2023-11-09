<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Create</title>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="pb-5">

    <div class="container">
        <h1>Create Customer</h1>
        <form id="customer-form">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Name: <span class="text-danger">*</span> </label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="image" class="form-label">Image:</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                </div>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Create Customer</button>
                <a href="{{ url('customers') }}" class="btn btn-danger ml-2">Back</a>
            </div>
        </form>
        <div id="error-message" style="color: red;"></div>
        <div id="message" style="color: green;"></div>
    </div>

    <script>
        const apiUrl = '/api/v1/customers';
        const errorDiv = document.getElementById('error-message');
        const messageDiv = document.getElementById('message');

        document.getElementById('customer-form').addEventListener('submit', (event) => {
            event.preventDefault(); // Prevent the default form submission behavior

            const name = document.getElementById('name').value;
            const image = document.getElementById('image').files[0];

            const formData = new FormData();
            formData.append('name', name);
            formData.append('image', image);

            fetch(apiUrl, {
                    method: 'POST',
                    body: formData,
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
                        messageDiv.textContent = 'Customer created successfully.';
                        clearFormFields(); // Optionally, clear the form fields
                    } else {
                        // Handle other errors
                        messageDiv.style.color = 'red';
                        messageDiv.textContent = 'Failed to create customer.';
                    }
                })
                .catch(error => {
                    // Handle network errors
                    console.error('Network error:', error);
                });

        });

        function clearFormFields() {
            // Optionally, clear form fields after successful submission
            document.getElementById('name').value = '';
            document.getElementById('image').value = '';
        }
    </script>
</body>

</html>