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

export async function usePostFetch(url, body) {
  const data = ref(null)
  const error = ref(null)

  await fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(body),
  })
    .then(async res => {
      const resData = await res.json();
      if (!res.ok) {
        throw new Error(resData.message || 'Error');
      }
      return resData
    })
    .then((json) => (data.value = json))
    .catch((err) => (error.value = err.message))

  return { data, error }
}
