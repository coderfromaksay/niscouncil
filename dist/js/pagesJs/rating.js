document.addEventListener("DOMContentLoaded",function(){const e=document.querySelectorAll(".rating-nav a"),n=document.querySelectorAll(".rating-section");e.forEach(t=>{t.addEventListener("click",function(t){t.preventDefault(),e.forEach(t=>t.classList.remove("active")),n.forEach(t=>t.classList.remove("active")),this.classList.add("active");t=this.getAttribute("data-rating");document.getElementById(t).classList.add("active")})})}),document.addEventListener("DOMContentLoaded",function(){let e=document.getElementById("shanyraqTable").getElementsByTagName("tbody")[0];var t=Array.from(e.rows).map(t=>{var e=(parseInt(t.cells[1].textContent.trim())||0)+(parseInt(t.cells[2].textContent.trim())||0);return t.cells[3].textContent=e,{row:t,overall:e}});t.sort((t,e)=>e.overall-t.overall),t.forEach(({row:t})=>e.appendChild(t))});