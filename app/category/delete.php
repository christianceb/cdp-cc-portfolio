<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Delete Category</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
<div class="container">
  <h1 class="display-3">Delete Category</h1>

  <a class="btn btn-primary" href="browse.php" role="button">Back to Categories</a>
  <a id="show" class="btn btn-secondary d-none" href="show.php" role="button">Back to Category</a>

  <div class="alerts"></div>
  
  <div class="category d-none">
    <p>Are you sure you want to delete the following category?</p>
  
    <dl></dl>
  
    <form action="/api/categories/delete.php" method="get">
      <input type="hidden" name="id">
      <button class="btn btn-danger" type="submit">Delete Category</button>
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
  const category_details_map = [
    { key: "id", name: "ID" },
    { key: "name", name: "Name" },
    { key: "code", name: "Code" },
    { key: "description", name: "Description" },
    { key: "created_at", name: "Created" },
    { key: "updated_at", name: "Updated" },
  ]
  let url = "/api/categories/readOne.php";
  let id = 0;

  // Set id
  if ( query_vars.has('id') ) {
    id = Math.floor(query_vars.get('id')); // Ensure we get an int
  }

  // Sanity check before we query and display retrieved category
  if ( id > 0 ) {
    url += "?" + (new URLSearchParams({ id: id })).toString();

    $.get({
      url: url,
      success: ( data, textStatus, jqXHR ) => {
        populate_category(data[0], category_details_map);
        set_button_parameters(id);

        // Unhide delete form and nav button
        $('.category, #show').removeClass('d-none');
      },
      error: ( jqXHR, textStatus, errorThrown ) => {
        populate([]); // Populate with nothing
        add_alert("error", `Error loading data: ${jqXHR.statusText} (${jqXHR.status})`);
      }
    });
  } else {
    // Error
    add_alert("error", `Invalid ID`);
  }

  // For when form submits
  $("form").submit((event)=> {
    // Prevent classic form behaviour
    event.preventDefault();

    // Clear existing alerts
    $('.alerts').html("");

    $.post({
      url: event.target.action += "?" + (new URLSearchParams({ id: id })).toString(),
      success: ( data, textStatus, jqXHR ) => {
        add_alert("success", `Successfully deleted category!`);

        // Disable submit
        $("form button").attr("disabled", "")

        // And destroy back to category link
        $("#show").remove();
      },
      error: ( jqXHR, textStatus, errorThrown ) => {
        add_alert("error", `Error deleting data: ${jqXHR.statusText} (${jqXHR.status})`);
      }
    });
  });
</script>
</body>
</html>