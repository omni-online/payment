import StripeCheckout from 'react-stripe-checkout'
import Head from 'next/head'

const noop = () => {}

export default () => (
  <main>
    <Head>
      <title>Omni Online LLC</title>
    </Head>
    <h1>Omni Online LLC</h1>
    <h3>Payment Processing Form</h3>

    <p>Use the button below to begin processing a payment. Be sure to include your contact email so we can match it with the invoice</p>

    {process.env.NODE_ENV === 'develop' && (<aside>STRIPE_KEY: {process.env.STRIPE_KEY}</aside>)}

    <StripeCheckout
      name='Omni Online LLC'
      token={noop}
      panelLabel='Pay Now'
      currency='USD'
      locale='en'
      stripeKey={process.env.STRIPE_KEY}>
      <button className='btn btn-success text-uppercase px-4 py-3'>
        Pay Now
      </button>
      <style jsx>{`
        button {
          position: relative;
          border-radius: 4px;
          background-color: #3ea8e5;
          background-image: linear-gradient(-180deg,#44b1e8,#3098de);
          box-shadow: 0 1px 0 0 rgba(46,86,153,.15), inset 0 1px 0 0 rgba(46,86,153,.1), inset 0 -1px 0 0 rgba(46,86,153,.4);
          font-size: 17px;
          line-height: 21px;
          height: 37px;
          font-weight: 700;
          text-shadow: 0 -1px 0 rgba(0,0,0,.12);
          color: #fff;
          cursor: pointer;
          transition: all .2s ease-in-out;
        }

        button:hover {
          background-image: linear-gradient(180deg,#328ac3,#277bbe);
        }

        aside {
          background-color: grey;
          padding: 1em;
        }
      `}</style>
    </StripeCheckout>
  </main>
)
