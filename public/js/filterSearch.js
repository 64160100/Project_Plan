// new window.Choices(document.querySelector(".choices-multiple"));

document.addEventListener('DOMContentLoaded', function () {
    const multiselect = new Choices('#advanced-multiselect', {
        removeItemButton: true, // Allow users to remove selected options
        searchEnabled: true,    // Enable the search functionality
        placeholderValue: 'ค้นหารายชื่อ...', // Placeholder for the dropdown
        noResultsText: 'ไม่พบข้อมูล', // Message for no search results
        itemSelectText: '',     // Removes "Press Enter to select" message
    });
});

