document.querySelectorAll('.icon input').forEach(function(input) {
  input.addEventListener('input', function() {
    var icon = this.parentNode.querySelector('i');
    if (this.value.trim() !== '') {
      icon.style.display = 'none';
    } else {
      icon.style.display = 'inline-block';
    }
  });
});