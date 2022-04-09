var enCoursCards = document.getElementsByClassName('enCours');
var finishedCards = document.getElementsByClassName('finished');
var allCards = document.getElementsByClassName('card');
var allForm = document.getElementById('btnradio');
var currentForm = document.getElementById('btnradio1');
var finishedForm = document.getElementById('btnradio2');

document.getElementById('searchInput').addEventListener('keyup', event => {
  allForm.onclick = showAllFormCards;
  currentForm.onclick = showCurrentFormCards;
  finishedForm.onclick = showFinishedFormCards;
  let username = event.target.value.toLowerCase();


  for (let i = 0; i < allCards.length; i++) {
    const currentName = allCards[i].textContent.toLowerCase();
    if (currentName.includes(username)) {
      allCards[i].style.display = 'block';
    } else {
      allCards[i].style.display = 'none';
    }
  }
})
const showAllFormCards = () => {
  for (let i = 0; i < allCards.length; i++) {
    allCards[i].style.display = 'block';
  }
}
const showCurrentFormCards = () => {
  for (let i = 0; i < allCards.length; i++) {
    allCards[i].style.display = 'none';
    enCoursCards[i].style.display = 'block';
  }
};
const showFinishedFormCards = () => {
  for (let i = 0; i < allCards.length; i++) {
    allCards[i].style.display = 'none';
    finishedCards[i].style.display = 'block';
  }
};

