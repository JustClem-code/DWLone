import { ref } from 'vue'

const isUser = ref(false)
const userName = ref('')

export function userStore() {
  const setIsUser = val => isUser.value = val
  const setUserName = val => userName.value = val
  const getIsUser = () => isUser.value
  const logOut = () => window.location.href = '/logout'

  return { isUser, userName, setIsUser, setUserName, getIsUser, logOut }
}
