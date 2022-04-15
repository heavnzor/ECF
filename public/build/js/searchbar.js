var enCoursCards = document.getElementsByClassName('enCours');
var finishedCards = document.getElementsByClassName('finished');
var allCards = document.getElementsByClassName('col-md-4 mx-auto d-flex flex-column my-auto py-3');
var allForm = document.getElementById('btnradio');
var currentForm = document.getElementById('btnradio1');
var finishedForm = document.getElementById('btnradio2');

document.getElementById('searchInput').addEventListener('keyup', function (event) {
    var username = event.target.value.toLowerCase();
    for (let i = 0; i < allCards.length; i++) {
      const currentName = allCards[i].textContent.toLowerCase();
      if (currentName.includes(username)) {
        allCards[i].style.visibility = 'visible';
      } else {
        allCards[i].style.visibility = 'collapse';
      }
    }
  });


allForm.addEventListener('click', event => {
  for (let i = 0; i < allCards.length; i++) {
    allCards[i].style.visibility = 'visible';
  }
});

currentForm.addEventListener('click', event => {
  for (let i = 0; i < allCards.length; i++) {
    if (allCards[i].className.includes('enCours')) {
      allCards[i].style.visibility = 'visible';
    } else {
      allCards[i].style.visibility = 'collapse';
    }
  }
});
finishedForm.addEventListener('click', event => {
  for (let i = 0; i < allCards.length; i++) {
    if (allCards[i].className.includes('finished')) {
      allCards[i].style.visibility = 'visible';
    } else {
      allCards[i].style.visibility = 'collapse';
    }
  }
});



