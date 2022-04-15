var inputFields = [
  { label: "name", type: "text" },
  { label: "category name", type: "text" },
  { label: "price", type: "number" },
  { label: "stock", type: "number" },
];
var additional = [];
var variation = [];
$(document).ready(function () {
  $("#addBtn").click(function () {
    var label = $("#addLabel").val();
    var type = $("#addType").val();
    if (label && type) {
      if (additional.indexOf(label) === -1) {
        additional.push(label);
        var html = `
           <div class="mb-3">
             <label for="${label}" class="form-label">${label}</label>
            <input type="${type}" class="form-control" name='additional[${label}]' required>
            <button class='deleteAdd' data-idx=${additional.indexOf(
              label
            )}>delete</button>
        </div>
        `;
        $("#addForm").append(html);
      }
    }
  });

  $("#addForm").on("click", ".deleteAdd", function () {
    $(this).parent().remove();
    additional.splice($(this).data("idx"), 1);
  });

  $("#addVar").click(function () {
    var label = $("#variationLabel").val();
    var value = $("#variationValue").val();
    var price = $("#variationPrice").val();

    if (label && value && price) {
      if (variation.indexOf(value) === -1) {
        variation.push(value);
        var html = `
           <div class="mb-3">
            <label for="${label}" class="form-label">${label}</label>
            <input type="text" class="form-control" name='variation[${label}][${value}][value]' value='${value}' required>
<input type="number" class="form-control" name='variation[${label}][${value}][price]' value='${price}' required>
            <button class='varDel' data-idx=${additional.indexOf(
              value
            )}>delete</button>
        </div>
        `;
        $("#addForm").append(html);
      }
    }
  });

  $("#addForm").on("click", ".varDel", function (e) {
    $(this).parent().remove();
    variation.splice($(this).data("idx"), 1);
  });

  $(".viewProduct").click(function () {
    var id = $(this).data("id");
    $.ajax({
      url: "/index/viewProduct",
      data: { id: id },
      method: "POST",
      dataType: "json",
    }).done(function (data) {
      $("#pid").html("Product - " + data._id.$oid);
      var html = `
      <table class='table'>
      <tr>
        <th>
          Name
        </th>
        <td>
        <input type="text" name="name" value="${data.name}">
        </td>
      </tr>
      <tr>
        <th>
        Category
        </th>
        <td>
       <input type="text" name="category" value="${data.category}">
        </td>
      </tr>
      <tr>
        <th>
        Price
        </th>
        <td>
         <input type="number" name="price" value="${data.price}">
        </td>
      </tr>
      <tr>
        <th>
        Stock
        </th>
        <td>
         <input type="number" name="stock" value="${data.stock}">
        </td>
      </tr>
      </table>
      <input type="hidden" name="id" value=${data._id.$oid} >
      `;

      if (data.additional) {
        console.log(data.additional);
        html += ` <h3>Additonal</h3><table class='table'>`;
        Object.entries(data.additional).map(function (item) {
          html += `
          <tr>
          <th> ${item[0]}</th><td>  <input type="text" name="additional[${item[0]}]" value="${item[1]}"></td>
          </tr>
          `;
        });
        html += "</table>";
      }

      if (data.variation) {
        html += ` <h3>Variations</h3>`;
        Object.entries(data.variation).map(function (item) {
          html += ` <h5>${item[0]}</h5><table class='table'>`;
          Object.entries(item[1]).map(function (it) {
            console.log(item);
            html += `
            <tr> <th> ${item[0]}</th><td><input type="text" name=variation[${item[0]}][${it[1].value}][value] value="${it[1].value}"> </td> 
            &nbsp;&nbsp;&nbsp;&nbsp; 
            <th>price</th><td><input type="number" name=variation[${item[0]}][${it[1].value}][price] value="${it[1].price}"> </td>
            `;
          });
          html += `</table>`;
        });
      }
      html += `<input type="submit" name='btn' class='btn btn-success btn-lg float-right mx-2' value="update"> 
       <input type="submit" name='btn' value="delete"  class='btn btn-danger btn-lg float-right'>`;
      $(".productData").html(html);
    });
  });
});
