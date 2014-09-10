package LDAP

import play.Logger

import java.util.Hashtable
import javax.naming._
import javax.naming.directory._
import javax.naming.ldap.LdapContext
import javax.naming.ldap.InitialLdapContext

object ActiveDirectory {
  private val contextFactory: String = "com.sun.jndi.ldap.LdapCtxFactory"
  private val domainName: String = "zauberstuhl.de"

  def auth(username: String, password: String): Boolean = {
    if (username == null || password == null) {
      return false
    }
    //bind by using the specified username/password
    val props: Hashtable[String, String] = new Hashtable[String, String]()
    val principalName: String = username + "@" + this.domainName
    val ldapURL: String = "ldap://" + this.domainName

    props.put(Context.SECURITY_PRINCIPAL, principalName)
    props.put(Context.SECURITY_CREDENTIALS, password)
    props.put(Context.INITIAL_CONTEXT_FACTORY, this.contextFactory)
    props.put(Context.PROVIDER_URL, ldapURL)

    try {
      val ctx = new InitialLdapContext(props, null)
      ctx.close()
    } catch {
      case e: javax.naming.CommunicationException => {
        Logger.warn("Failed to connect to "+ldapURL)
        return false
      }
      case e: NamingException => {
        Logger.warn("Failed to authenticate "+principalName)
        return false
      }
    }
    Logger.info("Successfully authenticated "+principalName)
    true
  }
}
