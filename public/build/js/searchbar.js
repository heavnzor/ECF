<script>
function myFunction() {
  // Declare variables
  var input, filter, ul, div, img, a, i, txtValue;
  input = document.getElementById('myInput');
  filter = input.value.toUpperCase();
  f = document.getElementById("myF");
  div = getElementById('form');

  // Loop through all list items, and hide those who don't match the search query
  for (i = 0; i < div.length; i++) {
    f = div[i].getElementsByTagName("p")[0];
    txtValue = a.textContent || a.innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      div[i].style.display = "";
    } else {
      div[i].style.display = "none";
    }
  }
}
</script>