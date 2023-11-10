<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Customer Details</title>
</head>

<body>
    <div class="container mt-5">
        <h1>Product Details</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title" id="productName"></h5><br>
                <img src="" alt="Product Image" id="productImage" class="img-fluid h-50 w-50 rounded">
            </div>
        </div>
        <a href="<?php echo url('/products'); ?>" class="btn btn-primary mt-3">Back to List</a>
    </div>

    <script>
        // Get the product ID from the URL
        const productId = window.location.pathname.split('/').pop();

        // console.log(productId);

        const apiUrl = `/api/v1/products/${productId}`;
        const productName = document.getElementById('productName');
        const productImage = document.getElementById('productImage');

        fetch(apiUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log(data); // Log the entire response object

                productName.textContent = `Name: ${data.name}`;

                if (data.image && isImageFileName(data.image)) {
                    productImage.src = `<?php echo url('/images/${data.image}'); ?>`;
                } else {
                    // If there is no valid image name, hide the image tag
                    productImage.style.display = 'none';
                }
                console.log(data);
            })
            .catch(error => {
                console.error('Error fetching product data:', error);
            });

        function isImageFileName(fileName) {
            // Define a regular expression pattern for common image file extensions
            const imageFilePattern = /\.(jpg|jpeg|png|gif|jfif)$/i;

            // Use the pattern to test if the file name matches
            return imageFilePattern.test(fileName);
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>


</html>