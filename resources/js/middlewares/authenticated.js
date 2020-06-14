import { $auth } from '../auth'

export default function auth({ next, router }) {
    console.log("ok")
  if (!$auth.check()) {
    return next({ name: 'login' });
  }
  return next();
}