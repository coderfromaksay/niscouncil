 $(document).ready(function() {

    // Поиск студентов при вводе
    $('#student-search').on('input', function() {
        const query = $(this).val().trim();
        if (query.length < 2) {
            $('#student-results').empty();
            return;
        }

        $.ajax({
            url: 'search_student.php',
            method: 'GET',
            data: { q: query },
            success: function(data) {
                let html = '';
                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(function(student) {
                        html += `
              <div class="student-result list-group-item list-group-item-action" 
                   style="cursor:pointer;"
                   data-id="${student.studentID}" 
                   data-name="${student.name} ${student.surname}" 
                   data-grade="${student.grade}">
                   ${student.name} ${student.surname} (${student.grade})
              </div>`;
                    });
                } else {
                    html = '<div class="text-muted">Ничего не найдено</div>';
                }
                $('#student-results').html(html);
            },
            error: function() {
                $('#student-results').html('<div class="text-danger">Ошибка при загрузке данных</div>');
            }
        });
    });

    // Выбор студента из результатов
    $(document).on('click', '.student-result', function() {
    const name = $(this).data('name');
    const id = $(this).data('id');
    const grade = $(this).data('grade');

    $('#student-search').val(`${name} (${grade})`);
    $('#selected-student-id').val(id);
    $('#student-results').empty();
});

});