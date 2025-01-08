document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('budget-years-container');
    document.querySelector('.add-year').addEventListener('click', function() {
        const newInput = document.createElement('div');
        newInput.className = 'input-group mb-3';
        newInput.innerHTML = `
            <input type="number" class="form-control" name="Budget_Year[]" placeholder="Enter budget year" required>
            <div class="input-group-append">
                <button class="btn btn-outline-secondary remove-year" type="button">-</button>
            </div>
        `;
        container.appendChild(newInput);
    });

    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-year')) {
            e.target.closest('.input-group').remove();
        }
    });
});