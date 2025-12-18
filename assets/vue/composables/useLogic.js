import { ref, onMounted } from 'vue';

export function useLogic() {

  const getNumberOfPackagesNotInducted = (pallet) => {
    return pallet?.packages?.filter(p => p.location === null).length;
  }

  const handleMenuAction = (actionKey, actionsMap,
  ) => {
    const fn = actionsMap[actionKey]
    if (fn) fn()
  }

  return { getNumberOfPackagesNotInducted, handleMenuAction };
}
