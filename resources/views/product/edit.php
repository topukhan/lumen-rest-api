<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Edit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="pb-5">

    <div class="container">
        <h1>Edit Product</h1>
        <form id="product-edit-form">
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
                    <button type="submit" class="btn btn-primary">Update Product</button>
                    <a href="<?php echo url('/products'); ?>" class="btn btn-danger ml-2">Back</a>
                    <div id="error-message" class="text-danger mt-2"></div>
                    <div id="message" class="text-success mt-2"></div>
                </div>
                <div class="col-md-6 mb-3">
                    <img src="" alt="Product Image" id="productImage" class="img-fluid h-50  rounded">
                </div>
            </div>
        </form>
    </div>

    <script>
        // Populate product info
        const productId = window.location.pathname.split('/').pop();
        const apiUrlShow = `/api/v1/products/${productId}`;
        const errorDiv = document.getElementById('error-message');
        const messageDiv = document.getElementById('message');
        fetch(apiUrlShow)
            .then(response => response.json())
            .then(data => {
                console.log(data);
                document.getElementById('name').value = `${data.name}`;

                // Display the existing image if it exists
                if (data.image && isImageFileName(data.image)) {
                    productImage.src = `<?php echo url('/images/${data.image}'); ?>`;
                } else {
                    productImage.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error fetching product data:', error);
                errorDiv.textContent = error
            });

        // extract the image name from the image element
        function isImageFileName(fileName) {
            const imageFilePattern = /\.(jpg|jpeg|png|gif|jfif)$/i;

            return imageFilePattern.test(fileName);
        }

        // update products
        const apiUrlUpdate = `/api/v1/products/update/${productId}`;

        document.getElementById('product-edit-form').addEventListener('submit', (event) => {
            event.preventDefault();

            const name = document.getElementById('name').value;
            const image = document.getElementById('image').files[0];

            const formData = new FormData();
            formData.append('name', name);
            if (image) {
                formData.append('image', image);
            }

            fetch(apiUrlUpdate, {
                    method: 'POST',
                    body: formData, // Use FormData for sending image data
                    headers: {
                        'Accept': 'application/json',
                    },
                    redirect: 'follow', // Allow the fetch to follow redirects
                })
                .then(response => {
                    if (response.status === 422) {
                        // The response status is 422, indicating validation errors
                        return response.json()
                            .then(data => {
                                messageDiv.style.color = 'red';
                                messageDiv.textContent = 'Validation errors: ' + Object.values(data.errors).join(', ');
                            });
                    }
                    if (response.ok) {
                        messageDiv.style.color = 'green';
                        messageDiv.textContent = 'Product updated successfully.';
                    } else {
                        errorDiv.style.color = 'red';
                        errorDiv.textContent = 'Failed to update product.';
                    }
                })
                .catch(error => {
                    // Handle network errors
                    console.error(error);
                    errorDiv.textContent = error
                });
        });
    </script>
</body>


</html>