
  function searchText(){
  document.getElementById ('searchInput').addEventListener ('keyup', event => {
    let username = event.target.value.toLowerCase ();
    let allNamesDOMCollection = document.getElementsByClassName ('card');

    for (let i = 0; i < allNamesDOMCollection.length; i++) {
      const currentName = allNamesDOMCollection[i].textContent.toLowerCase ();
      if (currentName.includes (username)) {
        allNamesDOMCollection[i].style.display = 'block';
      } else {
        allNamesDOMCollection[i].style.display = 'none';
      }
    }
  });
  }
    function getAll(){

  document.getElementByName ('btnradio').addEventListener ('onclick', event => {
  let username = event.target.value.toLowerCase ();
  let allNamesDOMCollection = document.getElementsByClassName ('card');

  for (let i = 0; i < allNamesDOMCollection.length; i++) {
    const currentName = allNamesDOMCollection[i].textContent.toLowerCase ();
    if (currentName.includes (username)) {
      allNamesDOMCollection[i].style.display = 'block';
    } else {
      allNamesDOMCollection[i].style.display = 'none';
    }
  }
});
    }
      function getEnCours(){

  document.getElementByName ('btnradio1').addEventListener ('onclick', event => {
  let username = event.target.value.toLowerCase ();
  let allNamesDOMCollection = document.getElementsByClassName ('enCours');

  for (let i = 0; i < allNamesDOMCollection.length; i++) {
    const currentName = allNamesDOMCollection[i].textContent.toLowerCase ();
    if (currentName.includes (username)) {
      allNamesDOMCollection[i].style.display = 'block';
    } else {
      allNamesDOMCollection[i].style.display = 'none';
    }
  }
});

      }
        function getFinished(){

  document.getElementByName ('btnradio2').addEventListener ('onclick', event => {
  let username = event.target.value.toLowerCase ();
  let allNamesDOMCollection = document.getElementsByClassName ('finished');

  for (let i = 0; i < allNamesDOMCollection.length; i++) {
    const currentName = allNamesDOMCollection[i].textContent.toLowerCase ();
    if (currentName.includes (username)) {
      allNamesDOMCollection[i].style.display = 'block';
    } else {
      allNamesDOMCollection[i].style.display = 'none';
    }
  }
});
        }


