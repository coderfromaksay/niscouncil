$(document).ready(function(){$("#student-search").on("input",function(){var t=$(this).val().trim();t.length<2?$("#student-results").empty():$.ajax({url:"search_student.php",method:"GET",data:{q:t},success:function(t){let e="";Array.isArray(t)&&0<t.length?t.forEach(function(t){e+=`
              <div class="student-result list-group-item list-group-item-action" 
                   style="cursor:pointer;"
                   data-id="${t.studentID}" 
                   data-name="${t.name} ${t.surname}" 
                   data-grade="${t.grade}">
                   ${t.name} ${t.surname} (${t.grade})
              </div>`}):e='<div class="text-muted">Ничего не найдено</div>',$("#student-results").html(e)},error:function(){$("#student-results").html('<div class="text-danger">Ошибка при загрузке данных</div>')}})}),$(document).on("click",".student-result",function(){var t=$(this).data("name"),e=$(this).data("id"),s=$(this).data("grade");$("#student-search").val(t+` (${s})`),$("#selected-student-id").val(e),$("#student-results").empty()})});