<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Categories</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
<div class="container">
  <h1 class="display-3">Categories</h1>

  <a class="btn btn-success" href="add.php" role="button">Add new category</a>

  <div class="alerts"></div>

  <table class="table table-dark">
    <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">Code</th>
        <th scope="col">Name</th>
        <th scope="col">Description</th>
        <th scope="col">Actions</th>
      </tr>
    </thead>
    <tbody>
      <!-- dust -->
    </tbody>
  </table>

  <nav>
    <ul class="pagination">
    </ul>
  </nav>
</div>

<script
  src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
  crossorigin="anonymous"></script>
<script src="/dist/scripts.js"></script>
<script>
  // Set global parameters
  const query_vars = new URLSearchParams(window.location.search);
  let page = 1;
  const url = "/api/categories/read.php";
  let query_url = url;

  if ( query_vars.has('page') ) {
    page = Math.floor(query_vars.get('page')); // Ensure we get an int
  }

  if ( page > 0 ) {
    query_url += "?" + (new URLSearchParams({ page: query_vars.get('page') })).toString();
  }

  $.get({
    url: query_url,
    success: ( data, textStatus, jqXHR ) => {
      populate(data.results);
      pagination(data.pages, page, window.location.pathname);
    },
    error: ( jqXHR, textStatus, errorThrown ) => {
      populate([]); // Populate with nothing
      add_alert("error", `Error loading data: ${jqXHR.statusText} (${jqXHR.status})`);
    }
  });
</script>
</body>
</html>