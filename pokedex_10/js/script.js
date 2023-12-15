const pokemonName = document.querySelector('.pokemon__name');
const pokemonNumber = document.querySelector('.pokemon__number');
const pokemonImage = document.querySelector('.pokemon__image');

const form = document.querySelector('.form');
const input = document.querySelector('.input__search');
const buttonPrev = document.querySelector('.btn-prev');
const buttonNext = document.querySelector('.btn-next');

let searchPokemon = 1;

const fetchPokemon = async (pokemon) => {
  const APIResponse = await fetch(`https://pokeapi.co/api/v2/pokemon/${pokemon}`);

  if (APIResponse.status === 200) {
    const data = await APIResponse.json();
    return data;
  }
}

const checkFavoriteStatus = async (pokemonName) => {
  const response = await fetch('../php/sistema_de_favorito/verificar_favorito.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ pokemonName: pokemonName }),
  });

  if (response.ok) {
    const data = await response.json();
    return data.isFavorite;
  } else {
    console.error('Erro ao verificar status de favorito');
    return false;
  }
}

const renderPokemon = async (pokemon) => {
  pokemonName.innerHTML = 'Loading...';
  pokemonNumber.innerHTML = '';

  const data = await fetchPokemon(pokemon);

  if (data) {
    pokemonImage.style.display = 'block';
    pokemonName.innerHTML = data.name;
    pokemonNumber.innerHTML = data.id;
    pokemonImage.src = data['sprites']['versions']['generation-v']['black-white']['animated']['front_default'];
    input.value = '';
    searchPokemon = data.id;

    const isFavorite = await checkFavoriteStatus(data.name);
    checkboxFavorite.checked = isFavorite;
  } else {
    pokemonImage.style.display = 'none';
    pokemonName.innerHTML = 'Not found';
    pokemonNumber.innerHTML = '';
  }
}

form.addEventListener('submit', (event) => {
  event.preventDefault();
  renderPokemon(input.value.toLowerCase());
});

buttonPrev.addEventListener('click', () => {
  if (searchPokemon > 1) {
    searchPokemon -= 1;
    renderPokemon(searchPokemon);
  }
});

buttonNext.addEventListener('click', () => {
  searchPokemon += 1;
  renderPokemon(searchPokemon);
});

renderPokemon(searchPokemon);

const checkboxFavorite = document.querySelector('.checkbox-favorite');

checkboxFavorite.addEventListener('change', async () => {
  if (checkboxFavorite.checked) {
    const response = await fetch('../php/sistema_de_favorito/favoritar_pokemon.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ pokemonName: pokemonName.innerHTML }),
    });

    if (response.ok) {
      alert('Pokémon favoritado com sucesso!');
    } else {
      console.error('Erro ao favoritar Pokémon');
      checkboxFavorite.checked = false;
    }
  } else {
    const response = await fetch('../php/sistema_de_favorito/desfavoritar_pokemon.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ pokemonName: pokemonName.innerHTML }),
    });

    if (response.ok) {
      alert('Pokémon removido dos favoritos com sucesso!');
    } else {
      console.error('Erro ao desfavoritar Pokémon');
      checkboxFavorite.checked = true;
    }
  }
});

const addPokemonToTeam = async (pokemonName) => {
  const response = await fetch('../php/sistema_de_times/adicionar_time.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ pokemonName: pokemonName }),
  });

  if (response.ok) {
    alert('Pokémon adicionado ao time com sucesso!');
  } else if (response.status === 409) {
    alert('Este Pokémon já está no seu time.');
  } else if (response.status === 400) {
    const data = await response.json();
    alert(data.message);
  } else {
    console.error('Erro ao adicionar Pokémon ao time');
  }
};

const removePokemonFromTeam = async (pokemonName) => {
  const response = await fetch('../php/sistema_de_times/remover_time.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ pokemonName: pokemonName }),
  });

  if (response.ok) {
    alert('Pokémon removido do time com sucesso!');
  } else {
    console.error('Erro ao remover Pokémon do time');
  }
};

const addToTeamButton = document.querySelector('.add-to-team-button');
const removeFromTeamButton = document.querySelector('.remove-from-team-button');

addToTeamButton.addEventListener('click', () => {
  addPokemonToTeam(pokemonName.innerHTML);
});

removeFromTeamButton.addEventListener('click', () => {
  removePokemonFromTeam(pokemonName.innerHTML);
});