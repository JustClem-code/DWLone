import { reactive } from 'vue'

/* export const userStore = reactive({
  user: null,
  isUser: null,
  setUser(user) {
    this.user = user;
  },
  getUser() {
    return this.user
  },
  setIsUser(isUser) {
    this.isUser = isUser;
  },
  getIsUser() {
    return this.isUser
  },
  clear() {
    this.user = null;
  },
  logOut() {
    window.location.href = '/logout'
  }
}) */

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
