document.addEventListener('DOMContentLoaded', function() {
    var dropdownToggle = document.querySelectorAll('.dropdown-toggle');
    var dropdownMenus = document.querySelectorAll('.dropdown-menu');
  
    for (var i = 0; i < dropdownToggle.length; i++) {
      dropdownToggle[i].addEventListener('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        var dropdownMenu = this.nextElementSibling;
        if (dropdownMenu.style.display === 'block') {
          dropdownMenu.style.display = 'none';
        } else {
          dropdownMenu.style.display = 'block';
        }
      });
  
      dropdownToggle[i].addEventListener('mouseenter', function() {
          var dropdownMenu = this.nextElementSibling;
          dropdownMenu.style.display = 'block';
      });
  
      dropdownToggle[i].addEventListener('mouseleave', function() {
          var dropdownMenu = this.nextElementSibling;
          dropdownMenu.style.display = 'block';
      });
  
      dropdownMenus[i].addEventListener('mouseenter', function() {
          var dropdownMenu = this.nextElementSibling;
          this.style.display = 'block';
      });
  
      dropdownMenus[i].addEventListener('mouseleave', function() {
          var dropdownMenu = this.nextElementSibling;
          this.style.display = 'none';
      });
    }
  
    // Close dropdown menu when clicking outside
    document.addEventListener('click', function(event) {
      if (!event.target.matches('.dropdown-toggle')) {
        for (var j = 0; j < dropdownMenus.length; j++) {
          dropdownMenus[j].style.display = 'none';
        }
      }
    });
  });
  
  