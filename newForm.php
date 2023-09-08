<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Picture URL</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .upload-form {
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        .upload-form h2 {
            margin: 0 0 20px;
            font-size: 1.5rem;
        }

        .upload-form label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .upload-form input[type="text"] {
            width: 93%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 15px;
            transition: border-color 0.3s ease-in-out;
        }

        .upload-form input[type="text"]:focus {
            border-color: #007bff;
        }

        .upload-form h3 {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .upload-form .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin-bottom: 15px;
        }

        .upload-form .checkbox-item {
            display: flex;
            align-items: center;
        }

        .upload-form input[type="checkbox"] {
            display: none;
        }

        .upload-form label.checkbox-label {
            display: inline-block;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .upload-form input[type="checkbox"]:checked + label.checkbox-label {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }

        .upload-form button[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .upload-form button[type="submit"]:hover {
            background-color: #0056b3;
        }
        
    </style>
</head>
<body>
    <div class="upload-form">
        <h2>Upload Picture URL</h2>
        <label for="url">Enter URL:</label>
        <input type="text" id="url" name="url" placeholder="https://example.com/image.jpg" required>

        <h3>Select Size:</h3>
        <div class="size-options checkbox-group">
            <?php
            $tshirtSizes = [
                "XS", "S", "M", "L", "XL", "2XL", "3XL", "4XL", "5XL"
            ];
            foreach ($tshirtSizes as $size) {
                echo '<div class="checkbox-item">';
                echo '<input type="checkbox" id="size-' . $size . '" name="size[]" value="' . $size . '">';
                echo '<label class="checkbox-label" for="size-' . $size . '">' . $size . '</label>';
                echo '</div>';
            }
            ?>
        </div>

        <h3>Select Color:</h3>
        <div class="color-options checkbox-group">
            <?php
            $tshirtColors = [
                "Black", "Navy", "Brown", "Red", "Yellow",
                "Army", "Pink", "Light Blue", "Silver",
                "White", "Berry", "Gold", "Mustard"
            ];
            foreach ($tshirtColors as $color) {
                echo '<div class="checkbox-item">';
                echo '<input type="checkbox" id="color-' . $color . '" name="color[]" value="' . $color . '">';
                echo '<label class="checkbox-label" for="color-' . $color . '">' . $color . '</label>';
                echo '</div>';
            }
            ?>
        </div>
        
        <button type="submit" id="urlBtn" name="submit">Upload</button>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27/dist/sweetalert2.all.min.js"></script>
    
  <script>
    $(document).ready(function(){
        $("#urlBtn").click(function(){
            if ($("#url").val() != "") {
                var URL = $("#url").val();
                var sizes = $(".size-options input[type='checkbox']:checked").map(function(){
                    return $(this).val();
                }).get();
                var colors = $(".color-options input[type='checkbox']:checked").map(function(){
                    return $(this).val();
                }).get();
                
                
                if (sizes.length === 0 || colors.length === 0) {
                    Swal.fire({
                        title: 'Please select at least one size and one color.',
                        icon: 'warning',
                    });
                    return;
                }
                Swal.fire({
                    title: 'Loading...',
                    html: '<div class="loader"></div>',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    type: "POST",
                    url: "https://test.dealsfordell.com/carolinRahul/newfunctionFile.php",
                    data: {
                        action: "CreatePrintProd",
                        imgUrl: URL,
                        sizes: sizes,
                        colors: colors
                    },
                    success: function (response) {
                        var resultPrint = JSON.parse(response);
                        var Id = resultPrint.id;
                        var name = resultPrint.name;
                        var externalID = resultPrint.externalID;
                        var previewImage = resultPrint.previewImage;
                        var varcolors = colors; 
                        var varsizes = sizes; 
                        Swal.close(); 
                        Swal.fire({
                            title: 'Product Created in PrintFul',
                            text: 'Click Submit to create product in Woo.' + Id,
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonText: 'Submit',
                            cancelButtonText: 'Cancel',
                        }).then((result) => {
                            if (result.isConfirmed) {
                              
                             Swal.fire({
                                            title: 'Loading...Please wait',
                                            html: '<div class="loader"></div>',
                                            showConfirmButton: false,
                                            allowOutsideClick: false,
                                            onBeforeOpen: () => {
                                                Swal.showLoading();
                                            }
                                        });
                             $.ajax({
                                    type: "POST",
                                    url: "https://test.dealsfordell.com/carolinRahul/newfunctionFile.php",
                                    data: {
                                        action: "CreateWooProd",
                                        printfulId: Id,
                                        name: name,
                                        externalID: externalID,
                                        previewImage: previewImage,
                                        varcolors: varcolors,
                                        varsizes: varsizes
                                    },
                                    success: function (response) {
                                        Swal.close();
                                        console.log(response);
                                        window.location.href =response;
                                    }
                                });
                            }
                        });
                    }
                });
            }
        });
    });
</script>
</body>
</html>
  
