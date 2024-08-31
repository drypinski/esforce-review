import { load } from 'recaptcha-v3'

export default defineNuxtPlugin(({ $config: { public: { RECAPTCHA_SITE_KEY } } }) => {
    return {
        provide: {
            recaptcha: {
                execute: async (action?: string): Promise<string|undefined> => {
                    if (null === RECAPTCHA_SITE_KEY) {
                        return
                    }

                    try {
                        const recaptcha = await load(RECAPTCHA_SITE_KEY, { autoHideBadge: true })

                        return await recaptcha.execute(action)
                    } catch (e: any) {
                        console.error(e.message)
                    }
                }
            }
        }
    }
})
