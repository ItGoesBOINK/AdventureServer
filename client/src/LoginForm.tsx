import { useState } from 'react';
import type { FormEvent } from 'react';

type LoginFormProps = {
    onLogin: (username: string, password: string) => Promise<void>;
};

export function LoginForm({ onLogin }: LoginFormProps) {
    const [username, setUsername] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');

    async function handleSubmit(event: FormEvent) {
        event.preventDefault();

        setError('');

        try {
            await onLogin(username, password);
        } catch (error) {
            if (error instanceof Error) {
                setError(error.message);
            } else {
                setError("Login Failed!");
            }
        }
    }

    return (
        <form onSubmit={handleSubmit}>
            <div>
                <label>
                    User Name
                    <input
                        type={"text"}
                        value={username}
                        onChange={event => setUsername(event.target.value)}
                    />
                </label>
            </div>

            <div>
                <label>
                    Password
                    <input
                        type={"password"}
                        value={password}
                        onChange={event => setPassword(event.target.value)}
                    />
                </label>
            </div>

            <button type="submit">Log In</button>

            {error && <p>{error}</p>}

        </form>
    );
}