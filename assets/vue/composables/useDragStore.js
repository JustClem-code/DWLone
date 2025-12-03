// useDragStore.js
import { ref } from 'vue';

const draggedItem = ref(null);

export function useDragStore() {
  const setDraggedItem = (item) => { draggedItem.value = item; };
  const getDraggedItem = () => draggedItem.value;

  return { draggedItem, setDraggedItem, getDraggedItem };
}
