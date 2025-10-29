import { ref } from 'vue'

export function useFetch(url) {
  const data = ref(null)
  const error = ref(null)

  fetch(url)
    .then((res) => res.json())
    .then((json) => (data.value = json))
    .catch((err) => (error.value = err))

  return { data, error }
}

export function formattedDateFr(d) {
  const date = new Date(d.date);
  return date.toLocaleString('fr-FR');
  // const date = new Date(d.date.replace(' ', 'T') + 'Z');
  // return date.toLocaleString('fr-FR', {
  //   year: 'numeric',
  //   month: 'long',
  //   day: '2-digit',
  //   hour: '2-digit',
  //   minute: '2-digit'
  // });
}
