import eslint from '@eslint/js';

export default [
    {
        ignores: ['node_modules/**'],
    },
    {
        ...eslint.configs.recommended,
        files: ['public/js/**/*.js'],
        languageOptions: {
            ecmaVersion: 2022,
            sourceType: 'script',
            globals: {
                document: 'readonly',
                fetch: 'readonly',
                window: 'readonly',
            },
        },
        linterOptions: {
            reportUnusedDisableDirectives: 'error',
        },
        rules: {
            ...eslint.configs.recommended.rules,
            eqeqeq: ['error', 'always'],
            'no-var': 'error',
            'prefer-const': 'error',
        },
    },
];
