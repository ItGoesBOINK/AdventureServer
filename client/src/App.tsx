import { useEffect, useState } from "react";
import {LoginForm} from './LoginForm';
import RegisterForm from './RegisterForm';
import VerifyEmail from './VerifyEmail';

type User = {
  username: string;
  email: string;
}

function App() {
  const [user, setUser] = useState<User | null>(null);
  const [message, setMessage] = useState('Checking Authentication...');

  useEffect(() => { loadUser(); }, []);

  async function register
  (
      email    :string,
      username :string,
      password :string
  ) :Promise<void>
  {
      const response = await fetch(
          'http://localhost:8000/api/register.php',
          {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
              },
              body: JSON.stringify({
                  email, username, password
              })
          }
      );

      const data = await response.json();

      if (!response.ok) {
          throw new Error(data.error ?? 'Registration Failed!');
      }
  }

  async function loadUser(){
    try {
      const response = await fetch(
          'http://localhost:8000/api/me.php',
          { credentials: 'include' }
      );

      if (!response.ok) {
        throw new Error('Not Authenticated');
      }

      const data: User = await response.json();

      setUser(data);
      setMessage('');

    } catch {
      setUser(null);
      setMessage('Not Authenticated');
    }
  }

  async function login(username: string, password: string) {
    const response = await fetch(
        'http://localhost:8000/api/login.php',
        {
          method: 'POST',
          credentials: 'include',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({username, password}),
        }
    );

    const data = await response.json();

    if (!response.ok)
    {
      throw new Error(data.error ?? 'Login Failed!');
    }

    await loadUser();
  }

  async function logout() {
    const response = await fetch(
        'http://localhost:8000/api/logout.php',
        {
          method: 'POST',
          credentials: 'include',
        }
    );

    const data = await response.json();

    if(!response.ok) {
      throw new Error(data.error ?? 'Login Failed!');
    }

    setUser(null);
  }


  if(window.location.pathname === '/verify') {
      return <VerifyEmail />
  }


  return (

      <div>
        <h1>Adventure Server</h1>

        {user ? (
            <div>
                <p>Username: {user.username}</p>
                <p>E-Mail: {user.email}</p>
                <button onClick={logout}>Log Out</button>
            </div>
        ) : (
            <>
                <p>{message}</p>
                <LoginForm onLogin={login} />
                <hr />
                <h2>Create an Account</h2>
                <RegisterForm onRegister={register} />
            </>
        )}
      </div>
  );
}

export default App;