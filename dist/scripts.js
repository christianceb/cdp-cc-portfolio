/**
 * Prints a bootstrap alert on the page.
 *
 * @param string type Set the type of the alert.
 * @param string message Sets the message of the alert
 * @return void
 */
function add_alert(type, message) {
  let classes = ['alert'];

  if (type === "error") {
    classes.push('alert-danger');
  } else if (type === "success") {
    classes.push('alert-success');
  } else {
    classes.push('alert-primary');
  }

  let alert_html = `<div class="${classes.join(' ')}" role="alert">${message}</div>`;

  $('.alerts').append(alert_html);
}

/**
 * Collate data from form into an object
 * @param array $fields Array of keys to map into a new category object
 * @return object The new category object
 */
function collate(fields) {
  let data = {};

  fields.forEach( (key)=> {
    data[key] = $(`#${key}`).val();
  } );

  return data;
}

/**
 * sprintf goodie which I blatantly stole from a stackoverflow answer that blatantly stole from
 * stackoverflow: https://stackoverflow.com/a/18234317
 */
String.prototype.formatUnicorn = String.prototype.formatUnicorn ||
function () {
  "use strict";
  var str = this.toString();
  if (arguments.length) {
    var t = typeof arguments[0];
    var key;
    var args = ("string" === t || "number" === t) ?
      Array.prototype.slice.call(arguments)
      : arguments[0];

    for (key in args) {
      str = str.replace(new RegExp("\\{" + key + "\\}", "gi"), args[key]);
    }
  }

  return str;
};

/**
 * Build pagination based on parameters
 * @return void
 */
function pagination(pages, current, url) {
  const page_template = `<li class="{classes}"><a class="page-link" href="{url}">{page}</a></li>`;
  let pagination_html = "";
  let formatUnicorn_parameters = {};

  for (let i = 1; i <= pages; i++) {
    formatUnicorn_parameters = {
      url: null,
      classes: ["page-item"],
      page: i
    };

    // Prettify URL of first page
    if (i > 1) {
      formatUnicorn_parameters.url = url + "?" + (new URLSearchParams({ page: i })).toString();
    } else {
      formatUnicorn_parameters.url = url;
    }

    // Set active class if current page, then join them.
    if (i === current) {
      formatUnicorn_parameters.classes.push("active");
    }
    formatUnicorn_parameters.classes = formatUnicorn_parameters.classes.join(" ");

    pagination_html += page_template.formatUnicorn(formatUnicorn_parameters);
  }

  $(".pagination").append(pagination_html);
}

/**
 * Populate tbody of a table based off on data fed
 *
 * @param array data List of data to format and print
 * @return void
 */
function populate( data ) {
  const row_template = `
    <tr>
      <th scope="row">{id}</th>
      <td>{code}</td>
      <td>{name}</td>
      <td>{description}</td>
      <td>
        <a class="btn btn-primary" href="show.php?id={id}">View</a>
        <a class="btn btn-secondary" href="edit.php?id={id}">Edit</a>
      </td>
    </tr>
  `;
  const row_empty_template = `<tr><td colspan="5" class="text-center"><em>dust</em></td></tr>`;

  // HTML of rows
  let rows = "";

  if ( data.length ) {
    data.forEach(element => {
      // Element conveniently mapped to match keys in row_template
      rows += row_template.formatUnicorn( element )
    });
  } else {
    rows += row_empty_template;
  }

  $("table tbody").append(rows);
}

/**
 * Populate page with category details
 *
 * @param object category The category as returned by the API
 * @param object map A map of properties to display in the page
 * @return void
 */
function populate_category(category, map) {
  let dl_html = "";

  map.forEach(detail => {
    let value = category[detail.key];
    if (value == null) {
      value = "-";
    }

    dl_html += `
      <dt>${detail.name}</dt>
      <dd>${value}</dd>
    `;
  });

  $("dl").append(dl_html);
}

/**
 * Set parameters in hidden buttons such as href values
 *
 * @param int id The value to be used in the id query var key
 * @return void
 */
function set_button_parameters(id) {
  let button_selectors = `#edit, #delete`;
  const button_query_vars = (new URLSearchParams({ id: id })).toString();

  $(button_selectors).each((index, element) => {
    if (typeof element.href !== "undefined") {
      element.href += "?" + button_query_vars;
      $(element).removeClass('d-none');
    }
  });
}