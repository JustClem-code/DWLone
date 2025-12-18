import mitt from 'mitt';
const emitter = mitt();

export default emitter;

export function useNotification() {

  const notifier = (type, message, message_2) => {
    emitter.emit('notify', { type: type, message: message, message_2: message_2 })
  }

  return { notifier };
}
