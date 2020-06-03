<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Add Category</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
<div class="container">
  <h1 class="display-3">Add Category</h1>

  <a class="btn btn-primary" href="browse.php" role="button">Go Back</a>

  <form action="/api/categories/create.php" method="post">
    <div class="alerts"></div>

    <div class="form-group">
      <label for="code">Code</label>
      <input type="text" class="form-control" id="code" name="code" value="" required>
    </div>

    <div class="form-group">
      <label for="name">Name</label>
      <input type="text" class="form-control" id="name" name="name" value="">
    </div>

    <div class="form-group">
      <label for="description">Description</label>
      <input type="text" class="form-control" id="description" name="description" value="" required>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</div>

<script
  src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
  crossorigin="anonymous"></script>
<script src="/dist/scripts.js"></script>
<script>
  // Define keys to collect here later
  const fields = [ "description", "name", "code" ];

  $("form").submit((event)=> {
    // Prevent classic form behaviour
    event.preventDefault();

    // Clear existing alerts
    $('.alerts').html("");

    $.post({
      url: event.target.action,
      data: JSON.stringify(collate(fields)),
      success: ( data, textStatus, jqXHR ) => {
        add_alert("success", `Successfully added new record!`);

        // Disable submit
        $("form button").attr("disabled", "")
      },
      error: ( jqXHR, textStatus, errorThrown ) => {
        add_alert("error", `Error saving data: ${jqXHR.statusText} (${jqXHR.status})`);
      }
    });
  });
</script>
</body>
</html>