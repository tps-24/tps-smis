<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script> -->
</body>
</html>
Enhanced Example with Select2
If you want to enhance the multiple select dropdown with additional features such as search capability, you can use the Select2 plugin.

Include Select2 CSS and JS:

html
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
Initialize Select2:

javascript
<script>
    $(document).ready(function() {
        $('#exampleFormControlSelect2').select2();
    });
</script>
Combined Example:

html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multiple Select Dropdown</title>
    <!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> -->
</head>
<body>
    <div class="container mt-5">
        <form>
            <div class="mb-3">
                <label for="exampleFormControlSelect2" class="form-label">Example Multiple Select</label>
                <select multiple class="form-control" id="exampleFormControlSelect2">
                    <option>Choose hii option for everything</option>
                    <option>Option 2</option>
                    <option>Option 3</option>
                    <option>Option 4</option>
                    <option>Option 5</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#exampleFormControlSelect2').select2();
        });
    </script>
</body>
</html>
With this setup, you'll have a Bootstrap 5.3.3 multiple select dropdown enhanced with Select2 for a better user experience. Feel free to tweak the example according to your needs! If you have any other questions or need further customization, just let me know! 😊24681012

1github.com
2github.com
3github.com
4github.com
5github.com
6github.com
7github.com
8github.com
9github.com
10github.com
11github.com
12github.com
13github.com
