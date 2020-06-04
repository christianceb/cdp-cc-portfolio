<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>View Category</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
<div class="container">
  <h1 class="display-3">View Category</h1>

  <a class="btn btn-primary" href="browse.php" role="button">Go Back</a>
  <a class="btn btn-success" href="add.php" role="button">Add new category</a>
  <a id="edit" class="btn btn-secondary d-none" href="edit.php">Edit</a>
  <a id="delete" class="btn btn-danger d-none" href="delete.php">Delete</a>

  <div class="alerts"></div>
  <dl></dl>

  <div class="products d-none">
    <hr>
    <h2>Products in this category:</h2>
    <table class="table table-dark">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Name</th>
          <th scope="col">Description</th>
          <th scope="col">Price</th>
        </tr>
      </thead>
      <tbody>
        <!-- dust -->
      </tbody>
    </table>
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

  // Sanity check
  if ( id > 0 ) {
    url += "?" + (new URLSearchParams({ id: query_vars.get('id') })).toString();

    $.get({
      url: url,
      success: ( data, textStatus, jqXHR ) => {
        populate_category(data[0], category_details_map);
        set_button_parameters(id);
        query_and_populate_products(id);
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
</script>
</body>
</html>