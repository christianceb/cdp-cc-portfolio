<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Edit Category</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
<div class="container">
  <h1 class="display-3">Edit Category</h1>
  
  <a class="btn btn-primary" href="browse.php" role="button">Go Back</a>

  <div class="alerts"></div>
  
  <div class="category d-none">
    <form action="/api/categories/update.php" method="post">
      <div class="form-group">
        <label for="code">Code</label>
        <input type="text" class="form-control" id="code" name="code" required>
      </div>
  
      <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name">
      </div>
  
      <div class="form-group">
        <label for="description">Description</label>
        <input type="text" class="form-control" id="description" name="description" required>
      </div>
  
      <input type="hidden" name="id">
  
      <button type="submit" class="btn btn-primary">Save</button>
    </form>
  </div>
</div>

<script
  src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
  crossorigin="anonymous"></script>
<script src="/dist/scripts.js"></script>
<script>
  // Set global parameters
  const query_vars = new URLSearchParams(window.location.search);
  // Define keys to collect here later
  const fields = [ "id", "description", "name", "code" ];
  let url = "/api/categories/readOne.php";
  let id = 0;

  // Set id
  if ( query_vars.has('id') ) {
    id = Math.floor(query_vars.get('id')); // Ensure we get an int
  }

  // Sanity check before we query and apply retrieved category to forms
  if ( id > 0 ) {
    url += "?" + (new URLSearchParams({ id: query_vars.get('id') })).toString();

    $.get({
      url: url,
      success: ( data, textStatus, jqXHR ) => {
        populate_category_edit(data[0], fields);
        $(".category").removeClass("d-none");
      },
      error: ( jqXHR, textStatus, errorThrown ) => {
        add_alert("error", `Error loading data: ${jqXHR.statusText} (${jqXHR.status})`);
      }
    });
  } else {
    add_alert("error", `Invalid ID`);
  }

  // For when form submits
  $("form").submit((event)=> {
    // Prevent classic form behaviour
    event.preventDefault();

    // Clear existing alerts
    $('.alerts').html("");

    $.post({
      url: event.target.action,
      data: JSON.stringify(collate(fields)),
      success: ( data, textStatus, jqXHR ) => {
        add_alert("success", `Successfully saved modified record!`);

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