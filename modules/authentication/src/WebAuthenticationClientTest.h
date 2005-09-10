/*------------------------------------------------------------------------------

    Copyright (c) 2004 Media Development Loan Fund
 
    This file is part of the LiveSupport project.
    http://livesupport.campware.org/
    To report bugs, send an e-mail to bugs@campware.org
 
    LiveSupport is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
  
    LiveSupport is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
 
    You should have received a copy of the GNU General Public License
    along with LiveSupport; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 
 
    Author   : $Author$
    Version  : $Revision$
    Location : $Source: /home/paul/cvs2svn-livesupport/newcvsrepo/livesupport/modules/authentication/src/WebAuthenticationClientTest.h,v $

------------------------------------------------------------------------------*/
#ifndef WebAuthenticationClientTest_h
#define WebAuthenticationClientTest_h

#ifndef __cplusplus
#error This is a C++ include file
#endif


/* ============================================================ include files */

#ifdef HAVE_CONFIG_H
#include "configure.h"
#endif

#include <cppunit/extensions/HelperMacros.h>

#include "LiveSupport/Core/BaseTestMethod.h"

#include "WebAuthenticationClient.h"

namespace LiveSupport {
namespace Authentication {

using namespace LiveSupport::Core;

/* ================================================================ constants */


/* =================================================================== macros */


/* =============================================================== data types */

/**
 *  Unit test for the WebAuthenticationClient class.
 *
 *  @author  $Author$
 *  @version $Revision$
 *  @see WebAuthenticationClient
 */
class WebAuthenticationClientTest : public BaseTestMethod
{
    CPPUNIT_TEST_SUITE(WebAuthenticationClientTest);
    CPPUNIT_TEST(firstTest);
    CPPUNIT_TEST(getVersionTest);
    CPPUNIT_TEST(preferencesTest);
    CPPUNIT_TEST_SUITE_END();

    private:
        /**
         *  The WebAuthenticationClient instance to test.
         */
        Ptr<WebAuthenticationClient>::Ref   wac;

    protected:

        /**
         *  A simple test.
         *
         *  @exception CPPUNIT_NS::Exception on test failures.
         */
        void
        firstTest(void)                         throw (CPPUNIT_NS::Exception);

        /**
         *  Test the getVersion() function.
         *
         *  @exception CPPUNIT_NS::Exception on test failures.
         */
        void
        getVersionTest(void)                    throw (CPPUNIT_NS::Exception);

        /**
         *  Test saving and load of user preferences.
         *
         *  @exception CPPUNIT_NS::Exception on test failures.
         */
        void
        preferencesTest(void)                   throw (CPPUNIT_NS::Exception);

    public:
        
        /**
         *  Set up the environment for the test case.
         */
        void
        setUp(void)                                     throw ();

        /**
         *  Clean up the environment after the test case.
         */
        void
        tearDown(void)                                  throw ();
};


/* ================================================= external data structures */


/* ====================================================== function prototypes */


} // namespace Authentication
} // namespace LiveSupport

#endif // WebAuthenticationClientTest_h

