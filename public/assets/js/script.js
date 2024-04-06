    getNewData(false)
    setInterval(function () {
        getNewData(false)
    }, 60000);
    function getNewData(showAlert){
           $.ajax({
            url: 'getData',
            type: "GET",
            dataType: "json",
            success: function (data) {
                console.log(data.length)
                if(showAlert && data.length<1)alert('Brak nowych wiadomości');
                data.forEach(element => {
                    $('table:first tr:last').after('<tr class = "'+element.id+'"><td style="display: none;"><input name="id" type="hidden" value="'+element.id+'"></td><td><input type="text" name="sender" value="'+element.sender+'"></td><td><input type="text" name="receiver" value="'+element.receiver+'"></td><td><input name="message" type="text" value="'+element.message+'"></td><td><input name="data" type="text" value="'+element.data+'"></td><td><input type="submit" value="Zapisz" onclick="submitRow('+element.id+')"></td></tr>');
                });
            }
            });
    }

    let colPrev = null;
    let sort = false;
    function sortTable(col) {
        var table, rows, switching, i, x, y, shouldSwitch;
        table = document.getElementById("table");
        switching = true;
        console.log([sort,colPrev]);
        if(colPrev != col){
            sort = false;
        }
        while (switching) {
            switching = false;
            rows = table.rows;
            for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("TD")[col];
            y = rows[i + 1].getElementsByTagName("TD")[col];
            if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase() && sort) {
                shouldSwitch = true;
                break;
            }
            if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase() && !sort) {
                shouldSwitch = true;
                break;
            }
            }
            if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            }
        }
        
        sort = !sort;
        colPrev = col;
    }
    function filter(column) {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("input");
    filter = input.value.toUpperCase();
    table = document.getElementById("table"); 
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        if(column >= 0){
            td = tr[i].getElementsByTagName("td")[column];
        }else{
            td = tr[i].getElementsByTagName("td")
        }
        if (td) {
            for (j = 0; j < td.length; j++) {
            
                txtValue = td[j].textContent || td[j].innerText;
                console.log(txtValue)
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                    break;
                } else {
                    tr[i].style.display = "none";
                }
            }  
        }     
    }
    }
    function submitRow(idRow) {
        form = document.createElement("form"); 
        form.method = "get"; 
        form.action = "/setData"; 
        console.log($("#"+idRow+" td"))
        $("."+idRow+" td").children().each(function() { 
              if(this.type.substring(0,6) == "select") { 
                  input = document.createElement("input"); 
                  input.type = "hidden";
                  input.name = this.name; 
                  input.value = this.value; 
                  form.appendChild(input);
              } else { 
                  $(this).clone().appendTo(form);
              }
      
          });
          form.style.display = "none"; 
          document.body.appendChild(form)           
          form.submit(); // NOW SUBMIT THE FORM THAT WE'VE JUST CREATED AND POPULATED
      }