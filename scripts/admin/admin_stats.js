function filterTab(type, event) {
    document.querySelectorAll('.fil-btn').forEach(btn => btn.classList.remove('active'));
    if(event) event.currentTarget.classList.add('active');
    
    const groups = document.querySelectorAll('#filter-group');

    groups.forEach(group => {
        if (type === 'overview') {
            group.style.display = 'block'; 
        } else {
            if (group.dataset.category === type) {
                group.style.display = 'block';
            } else {
                group.style.display = 'none';
            }
        }
    });
}

function reportPdf() {
    window.open('admin_generate_pdf.php', '_blank');
}